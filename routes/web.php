<?php

use App\Livewire\Home;
use App\Livewire\Cart\CartPage;
use App\Livewire\About\AboutPage;
use App\Livewire\Besmo\BesmoPage;
use App\Livewire\Careers\Careers;
use App\Livewire\Order\OrderPage;
use App\Livewire\Panel\Dashboard;
use App\Livewire\Services\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Contact\ContactPage;
use App\Livewire\Service\ServicePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

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
Route::get('/besmo', BesmoPage::class);
Route::post('/contactus/AddContact', [Controller::class, 'AddContact']);
Route::post('/subscribe/AddSubscribe', [Controller::class, 'AddSubscribe']);
Route::post('/signup', [Controller::class, 'signup'])->name('signup');
Route::post('/login', [Controller::class, 'login'])->name('login');

Route::post('/confirm-code', [Controller::class, 'confirmCode'])->name('confirm-code');
Route::post('/logout', [Controller::class, 'logout'])->name('logout');

// order
Route::get('/order/{slug}/{service_id}', OrderPage::class)->name('order');
// check guard is user
Route::get('/panel', Dashboard::class);
// cart
Route::get('/cart', CartPage::class)->name('cart');
Route::post('/addToCart', [CartController::class, 'addToCart'])->name('addToCart');
