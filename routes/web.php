<?php

use App\Http\Controllers\Controller;
use App\Livewire\Home;
use App\Livewire\About\AboutPage;
use App\Livewire\Careers\Careers;
use App\Livewire\Services\Services;
use App\Livewire\Contact\ContactPage;
use App\Livewire\Service\ServicePage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class); 
Route::get('/services', Services::class); 
Route::get('/services/service/{slug}', ServicePage::class); 
Route::post('/services/AddRequest', [Controller::class, 'AddRequest']);
Route::get('/careers', Careers::class); 
Route::get('/aboutus', AboutPage::class);  
Route::get('/contactus', ContactPage::class);  
Route::post('/contactus/AddContact', [Controller::class, 'AddContact']);

