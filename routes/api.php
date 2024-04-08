<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/products/{product_id}/comments', [CommentController::class, 'store']);
    Route::patch('/comments/{comment}/like', [CommentController::class, 'like']);
    Route::patch('/comments/{comment}/dislike', [CommentController::class, 'dislike']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});



// Route::get('/verify-email', [VerificationController::class, 'verifyEmail']);


Route::any('/email/verify', function () {
    return response()->json(['message' => 'Please verify your email address.'], 403);
})->middleware('auth:sanctum')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json(['message' => 'Email verified successfully.'], 200);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();

//     return response()->json(['message' => 'Verification email sent.'], 200);
// })->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');



