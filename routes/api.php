<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
Route::get('get-Clinic-services/{id}', [UserController::class, 'getClinicServices']); 
Route::get('all-services-clinic/{user_id}', [UserController::class, 'allServicesClinic']);   

// n8n
Route::get('totalProvider', [UserController::class, 'totalProvider']); 


// panel api

Route::get('get-appointments/{id}', [UserController::class, 'getAppointments']);
Route::get('get-user-info/{id}', [UserController::class, 'getUserInfo']);  

