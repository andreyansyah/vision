<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['chat_session_id', 'code_project', 'sender', 'message', 'image_path', 'status_send', 'status_reply'];

    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class);
    }

    public function getExecutionStatus()
    {
        if ($this->status_reply === 'pending' || $this->status_reply === 'processing') {
            return 'running';
        }
        
        // Cari pesan PC yang membalas pesan user ini
        $reply = ChatMessage::where('chat_session_id', $this->chat_session_id)
            ->where('sender', 'pc')
            ->where('id', '>', $this->id)
            ->orderBy('id', 'asc')
            ->first();
            
        if (!$reply) {
            return 'running';
        }
        
        $msgText = $reply->message;
        if (str_contains($msgText, 'Eksekusi Gagal') || str_contains($msgText, 'Error System') || str_contains($msgText, '❌') || str_contains($msgText, 'Gagal')) {
            return 'failed';
        }
        
        return 'success';
    }
}
