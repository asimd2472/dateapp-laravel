<?php

use App\Http\Controllers\Api\OtpAuthController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/send-otp', [OtpAuthController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpAuthController::class, 'verifyOtp']);



Route::get('/get-city', [CommonController::class, 'getCity']);
Route::get('/cityUpdate', [CommonController::class, 'cityUpdate']);

Route::middleware('auth:api')->group(function () {
	// Route::get('/inventory/types', [UserInventryController::class, 'getTypes']);
	Route::post('/register-update-profile', [ProfileController::class, 'register_update_profile']);
	Route::post('/Interests-update', [ProfileController::class, 'Interests_update']);
	Route::get('/profiles', [ProfileController::class, 'index']);

	
});




