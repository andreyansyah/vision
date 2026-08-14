<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Tampilkan halaman daftar catatan
     */
    public function index()
    {
        $notes = Note::orderBy('updated_at', 'desc')->get();
        return view('notes', compact('notes'));
    }

    /**
     * Simpan catatan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string'
        ]);

        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'note' => $note
            ]);
        }

        return redirect()->back()->with('success', 'Catatan berhasil dibuat!');
    }

    /**
     * API: Perbarui catatan yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string'
        ]);

        $note = Note::findOrFail($id);
        $note->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json([
            'status' => 'success',
            'note' => $note
        ]);
    }

    /**
     * API: Hapus catatan dari database
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Catatan berhasil dihapus.'
        ]);
    }
}
