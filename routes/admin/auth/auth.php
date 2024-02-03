<?php

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
// login and register routes
Route::post('/register',[\App\Http\Controllers\AuthController::class,'register']);
Route::post('/login',[\App\Http\Controllers\AuthController::class,'login']);

//logout routes
Route::middleware('auth:sanctum')
    ->get('/logout',[\App\Http\Controllers\AuthController::class,'logout']);

// social media routes
Route::get('/auth/{provider}/redirect',[\App\Http\Controllers\ProviderController::class,'redirect']);
Route::get('/auth/{provider}/callback',[\App\Http\Controllers\ProviderController::class,'callback']);


//Route::post('password/email', [\App\Http\Controllers\ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
//Route::post('password/reset', [\App\Http\Controllers\ResetPasswordController::class, 'reset'])->name('password.reset');
//Route::post('email/verify', [\App\Http\Controllers\VerificationController::class, 'verify'])->name('verification.verify');
Route::post(
    '/forgot-password',
    [App\Http\Controllers\ResetPasswordController::class, 'forgotPassword']
);
Route::post(
    '/verify/pin',
    [App\Http\Controllers\ResetPasswordController::class, 'verifyPin']
);
Route::post(
    '/reset-password',
    [App\Http\Controllers\ResetPasswordController::class, 'resetPassword']
);


Route::post('/auth/contact',[\App\Http\Controllers\ContactController::class,'store']);
