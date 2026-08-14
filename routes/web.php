<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('index');
});

Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);

Route::get('/schedule', function () {
    return view('schedule');
});

Route::get('/notes', [NoteController::class, 'index']);
Route::post('/notes', [NoteController::class, 'store']);
Route::put('/notes/{id}', [NoteController::class, 'update']);
Route::delete('/notes/{id}', [NoteController::class, 'destroy']);

Route::get('/project-detail', [ChatController::class, 'show'])->name('project.detail');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/sessions/{session_id}/messages', [ChatController::class, 'getSessionMessages']);
Route::get('/chat/sessions/search', [ChatController::class, 'searchSessions']);
Route::get('/task-history', [ChatController::class, 'taskHistory'])->name('task.history');
