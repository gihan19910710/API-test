<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserManagmentController;
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

Route::post('login', [ApiController::class, 'login']);
Route::post('createUser', [UserManagmentController::class, 'createUser']);

// ---------------------Forgot Password API--------------------------------
Route::post('forgetPassword', [ApiController::class, 'forgetPassword']);

//------------------------submit Reset Password Form ----------------------------
Route::post('/submitResetPasswordForm', [ApiController::class, 'submitResetPasswordForm']);


Route::middleware('auth:api')->post('getUserDetailsIntoDashboard', [UserManagmentController::class, 'getUserDetailsForDashboard']);
