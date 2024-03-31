<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {
    Route::post('post/logout', [AuthController::class, 'logout']);
    Route::post('post/create', [PostController::class, 'create']);
    Route::post('post/update', [PostController::class, 'update']);
    Route::post('post/delete', [PostController::class, 'delete']);
    Route::get('post', [PostController::class, 'posts']);

    // comment
    Route::post('comment/create', [CommentController::class, 'create']);
    Route::post('comment/update', [CommentController::class, 'update']);
    Route::post('comment/delete', [CommentController::class, 'delete']);
    Route::get('post/comment', [CommentController::class, 'comments']);

    Route::post('post/like', [LikeController::class, 'like']);
});
