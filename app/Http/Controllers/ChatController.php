<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Tampilkan detail proyek dan riwayat chat percakapan.
     */
    public function show(Request $request)
    {
        $projectName = $request->query('project', 'Xyora');
        
        // Cari proyek aktif
        $project = Project::where('name', $projectName)->first();
        if (!$project) {
            // Jika proyek tidak ditemukan, buat atau arahkan ke default
            $project = Project::first() ?: Project::create([
                'name' => $projectName,
                'code_project' => Str::slug($projectName),
                'logo' => 'project-logos/xyora.svg'
            ]);
        }

        // Ambil semua proyek untuk dropdown switcher
        $projects = Project::orderBy('name')->get();

        // Ambil 12 sesi percakapan terbaru milik proyek ini
        $sessions = ChatSession::where('project_id', $project->id)
            ->orderBy('updated_at', 'desc')
            ->take(12)
            ->get();

        // Ambil sesi aktif
        $activeSessionId = $request->query('session_id');
        $activeSession = null;
        $messages = [];

        if ($activeSessionId) {
            $activeSession = ChatSession::where('id', $activeSessionId)
                ->where('project_id', $project->id)
                ->first();
            
            if ($activeSession) {
                $messages = ChatMessage::where('chat_session_id', $activeSession->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('project-detail', compact('project', 'projects', 'sessions', 'activeSession', 'messages'));
    }

    /**
     * Simpan pesan baru dari aplikasi dan simulasikan jawaban dari komputer rumah.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'message' => 'required|string',
            'image' => 'nullable|image|max:10240', // Max 10MB
        ]);

        $project = Project::findOrFail($request->project_id);
        $sessionId = $request->input('chat_session_id');

        // Jika belum ada sesi percakapan aktif, buat baru
        if (!$sessionId) {
            $title = Str::limit($request->message, 30, '...');
            $session = ChatSession::create([
                'project_id' => $project->id,
                'title' => $title
            ]);
            $sessionId = $session->id;
        } else {
            $session = ChatSession::findOrFail($sessionId);
            // Sentuh timestamp sesi agar naik ke atas daftar terbaru
            $session->touch();
        }

        // Upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $cleanName = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName = time() . '_' . $cleanName;
            $file->move(public_path('uploads/chat_images'), $fileName);
            $imagePath = 'uploads/chat_images/' . $fileName;
        }

        // 1. Simpan pesan dari user ke database (HP)
        $userMessage = ChatMessage::create([
            'chat_session_id' => $sessionId,
            'code_project' => $project->code_project,
            'sender' => 'user',
            'message' => $request->message,
            'image_path' => $imagePath,
            'status_send' => 'pending',
            'status_reply' => 'pending'
        ]);

        // 2. Kirim Webhook secara real-time ke Komputer Rumah
        $webhookUrl = env('PC_RUNNER_WEBHOOK_URL');
        if ($webhookUrl) {
            try {
                $imageUrl = $imagePath ? url($imagePath) : null;
                
                // Kirim request ke runner.py di komputer rumah (timeout 3 detik)
                $response = \Illuminate\Support\Facades\Http::timeout(3)->post($webhookUrl, [
                    'id' => $userMessage->id,
                    'message' => $userMessage->message,
                    'code_project' => $userMessage->code_project,
                    'chat_session_id' => $userMessage->chat_session_id,
                    'image_url' => $imageUrl
                ]);

                if ($response->successful()) {
                    // Update status bahwa pesan sukses terkirim ke PC rumah
                    $userMessage->update(['status_send' => 'sent']);
                }
            } catch (\Exception $e) {
                // Jika komputer rumah offline, status tetap 'pending'
                // Tulis ke log agar developer tahu
                logger()->error("Gagal mengirim webhook ke Komputer Rumah: " . $e->getMessage());
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'session_id' => $sessionId,
                'message_id' => $userMessage->id,
                'status_send' => $userMessage->status_send,
                'status_reply' => $userMessage->status_reply,
            ]);
        }

        return redirect()->route('project.detail', [
            'project' => $project->name,
            'session_id' => $sessionId
        ])->with('success', 'Perintah dikirim ke Komputer Rumah!');
    }

    /**
     * API Endpoint: Ambil daftar perintah tertunda untuk Komputer Rumah (PC polling)
     */
    public function getPendingCommands($code_project)
    {
        $project = Project::where('code_project', $code_project)->first();
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        // Ambil semua pesan user yang belum terkirim ke PC (dengan relasi di-load)
        $pendingMessages = ChatMessage::with('chatSession.project')
        ->whereHas('chatSession', function ($query) use ($project) {
            $query->where('project_id', $project->id);
        })
        ->where('sender', 'user')
        ->where('status_send', 'pending')
        ->get();

        // Tandai sebagai 'sent' (telah ditarik oleh PC)
        foreach ($pendingMessages as $msg) {
            $msg->update(['status_send' => 'sent']);
        }

        return response()->json($pendingMessages);
    }

    /**
     * API Endpoint: Ambil seluruh perintah tertunda dari semua proyek (polling terpusat)
     */
    public function getAllPendingCommands()
    {
        $pendingMessages = ChatMessage::with('chatSession.project')
        ->where('sender', 'user')
        ->where('status_send', 'pending')
        ->get();

        // Tandai sebagai 'sent'
        foreach ($pendingMessages as $msg) {
            $msg->update(['status_send' => 'sent']);
        }

        return response()->json($pendingMessages);
    }

    /**
     * API Endpoint: Komputer Rumah mengirimkan balasan eksekusi kerja
     */
    public function postReply(Request $request, $message_id)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        $userMessage = ChatMessage::findOrFail($message_id);
        
        // Update status pesan user asli
        $userMessage->update([
            'status_send' => 'sent',
            'status_reply' => 'replied'
        ]);

        // Buat balasan pesan dari komputer rumah
        $reply = ChatMessage::create([
            'chat_session_id' => $userMessage->chat_session_id,
            'code_project' => $userMessage->code_project,
            'sender' => 'pc',
            'message' => $request->reply_message,
            'status_send' => 'sent',
            'status_reply' => 'replied'
        ]);

        return response()->json([
            'message' => 'Reply posted successfully',
            'data' => $reply
        ]);
    }

    /**
     * API Webhook: Menerima balasan dari PC rumah dengan format payload {"message_id": X, "reply_message": "Y"}
     */
    public function postReplyWebhook(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:chat_messages,id',
            'reply_message' => 'required|string',
            'status_reply' => 'nullable|string'
        ]);

        $userMessage = ChatMessage::findOrFail($request->message_id);
        $statusReply = $request->input('status_reply', 'replied');
        
        // Update status pesan asli user
        $userMessage->update([
            'status_send' => 'sent',
            'status_reply' => $statusReply
        ]);

        // Buat pesan baru dari PC
        $reply = ChatMessage::create([
            'chat_session_id' => $userMessage->chat_session_id,
            'code_project' => $userMessage->code_project,
            'sender' => 'pc',
            'message' => $request->reply_message,
            'status_send' => 'sent',
            'status_reply' => $statusReply
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reply saved successfully',
            'data' => $reply
        ]);
    }

    /**
     * API: Ambil semua pesan untuk chat session tertentu (untuk ajax polling di frontend)
     */
    public function getSessionMessages($session_id)
    {
        $session = ChatSession::findOrFail($session_id);
        $messages = $session->messages()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'messages' => $messages
        ]);
    }

    /**
     * API: Telusuri sesi automasi/chat berdasarkan query pencarian
     */
    public function searchSessions(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'query' => 'nullable|string|max:100'
        ]);

        $query = $request->input('query');
        $projectId = $request->input('project_id');

        $sessions = ChatSession::where('project_id', $projectId)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%');
            })
            ->orderBy('updated_at', 'desc')
            ->take(15) // Batasi hasil pencarian
            ->get();

        return response()->json([
            'status' => 'success',
            'sessions' => $sessions
        ]);
    }

    /**
     * Tampilkan riwayat tugas (automasi/eksekusi CLI) dari database
     */
    public function taskHistory(Request $request)
    {
        $projectName = $request->query('project');
        $project = Project::where('name', $projectName)->first();
        
        if (!$project) {
            // Fallback ke proyek pertama jika nama proyek tidak terisi / tidak valid
            $project = Project::first() ?: Project::create([
                'name' => 'Xyora',
                'code_project' => 'xyora',
                'logo' => 'project-logos/xyora.svg'
            ]);
        }

        $tasks = ChatMessage::with(['chatSession.project'])
            ->whereHas('chatSession', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->where('sender', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        if ($request->ajax() || $request->wantsJson()) {
            $tasks->getCollection()->transform(function ($task) {
                return [
                    'id' => $task->id,
                    'chat_session_id' => $task->chat_session_id,
                    'message' => $task->message,
                    'short_message' => \Illuminate\Support\Str::limit($task->message, 120),
                    'status' => $task->getExecutionStatus(),
                    'created_at_human' => $task->created_at->diffForHumans(),
                    'project_name' => $task->chatSession && $task->chatSession->project ? $task->chatSession->project->name : 'Unknown Project',
                    'project_logo' => asset($task->chatSession && $task->chatSession->project ? $task->chatSession->project->logo : 'project-logos/xyora.svg'),
                ];
            });
            return response()->json($tasks);
        }

        return view('task-history', compact('tasks', 'project'));
    }
}
