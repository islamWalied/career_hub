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
