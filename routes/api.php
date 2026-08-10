<?php

use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('signup', [UserController::class, 'signup']);
Route::post('login', [UserController::class, 'login']);
Route::get('get-users', [UserController::class, 'getUsers']);
Route::get('get-user/{id}', [UserController::class, 'getUser']);

// vascular cosmetics
Route::get('all-services-clinic/{user_id}', [UserController::class, 'allServicesClinic']);
Route::post('vascular-cosmetic-update-service', [UserController::class, 'vascularCosmeticUpdateService']);
Route::get('get-Clinic-services/{id}', [UserController::class, 'getClinicServices']);
Route::get('get-vascular-care-clinic-services/{id}', [UserController::class, 'getVascularCareClinicServices']);
Route::get('get-vascular-beauty-clinic-services/{id}', [UserController::class, 'getVascularBeautyClinicServices']);
Route::get('get-vascular-hormone-clinic-services/{id}', [UserController::class, 'getVascularHormoneClinicServices']);

// n8n
Route::get('totalProvider', [UserController::class, 'totalProvider']);

// panel api

Route::get('get-appointments/{id}', [UserController::class, 'getAppointments']);
// Route::get('get-user-info/{id}', [UserController::class, 'getUserInfo']);
Route::get('get-contries', [PublicController::class, 'getCountries']);
Route::get('get-provinces/{id}', [PublicController::class, 'getProvinces']);
Route::get('get-cities/{id}', [PublicController::class, 'getCities']);
// Route::get('update-user-info/{id}', [PublicController::class, 'updateUserInfo']);

Route::middleware('auth:sanctum')->post('update-user-info', [PublicController::class, 'updateUserInfo']);
Route::middleware('auth:sanctum')->post('check-referral', [PublicController::class, 'checkReferral']);
Route::middleware('auth:sanctum')->post('upload-avatar', [PublicController::class, 'uploadAvatar']);
Route::middleware('auth:sanctum')->get('get-user-info/{id}', [PublicController::class, 'getUserInfo']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('referrals/bootstrap', [ReferralController::class, 'bootstrap']);
    Route::get('referral-destinations', [ReferralController::class, 'destinations']);
    Route::get('referral-destinations/{business}/services', [ReferralController::class, 'services']);
    Route::get('referral-customers/by-phone', [ReferralController::class, 'customerByPhone']);
    Route::get('referral-customers', [ReferralController::class, 'customers']);
    Route::put('referral-services/{type}/{service}/settings', [ReferralController::class, 'updateServiceSettings']);
    Route::post('referrals', [ReferralController::class, 'store']);
    Route::get('referrals/{referral}', [ReferralController::class, 'show']);
    Route::post('referrals/{referral}/actions/{action}', [ReferralController::class, 'action']);
});
  