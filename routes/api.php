<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChatController;

Route::get('/projects/{code_project}/pending', [ChatController::class, 'getPendingCommands']);
Route::get('/messages/pending', [ChatController::class, 'getAllPendingCommands']);
Route::post('/messages/{message_id}/reply', [ChatController::class, 'postReply']);
Route::post('/webhook/reply', [ChatController::class, 'postReplyWebhook']);
