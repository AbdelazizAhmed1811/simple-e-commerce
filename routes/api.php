<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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





/*
    Authentication routes 
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('products', [ProductController::class, 'index']);


// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('users', [AuthController::class, 'index']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);



    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/products/{product}/delete', [ProductController::class, 'destroy']);
    Route::post('/products/{product}/update', [ProductController::class, 'update']);



    // Route::patch('/comments/{comment}/like', [CommentController::class, 'like']);
    // Route::patch('/comments/{comment}/dislike', [CommentController::class, 'dislike']);
    // Route::put('/comments/{comment}', [CommentController::class, 'update']);
    // Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);


    Route::post('/products/{product_id}/comments', [CommentController::class, 'store']);
    Route::post('/comments/like/{comment}', [CommentController::class, 'like']);
    Route::post('/comments/dislike/{comment}', [CommentController::class, 'dislike']);
    Route::post('/comments/update/{comment}', [CommentController::class, 'update']);
    Route::post('/comments/delete/{comment}', [CommentController::class, 'destroy']);
});

Route::any('/email/verify', function () {
    return response()->json(['message' => 'Please verify your email address.'], 403);
})->middleware('auth:sanctum')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email verified successfully.'], 200);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');



