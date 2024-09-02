<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('/posts', [PostController::class, 'index']);
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');