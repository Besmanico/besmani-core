<?php

use App\Livewire\Home;
use App\Livewire\Cart\CartPage;
use App\Livewire\Terms\TermPage;
use App\Livewire\About\AboutPage;
use App\Livewire\Besmo\BesmoPage;
use App\Livewire\Careers\Careers;
use App\Livewire\Order\OrderPage;
use App\Livewire\Panel\Dashboard;
use App\Livewire\Services\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Contact\ContactPage;
use App\Livewire\Privacy\PrivacyPage;
use App\Livewire\Service\ServicePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Livewire\Agreement\AgreementPage;
use App\Livewire\Panel\Invoice\InvoicePage;
use App\Livewire\Panel\Payment\PaymentPage;
use App\Livewire\Panel\Profile\ProfilePage;
use App\Http\Controllers\Api\UserController;
use App\Livewire\Panel\Business\BusinessPage;

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
Route::get('/raw', function () {
    return response()->json(['ok' => true]);
});
Route::get('/', Home::class);
Route::get('/services', Services::class);
Route::get('/services/service/{slug}', ServicePage::class);
Route::post('/services/AddRequest', [Controller::class, 'AddRequest']);
Route::get('/careers', Careers::class);
Route::get('/terms', TermPage::class);
Route::get('/privacy', PrivacyPage::class);
Route::get('/service-agreement', AgreementPage::class);

Route::get('/aboutus', AboutPage::class);
Route::get('/contactus', ContactPage::class);
Route::get('/besmo', BesmoPage::class);
Route::post('/contactus/AddContact', [Controller::class, 'AddContact']);
Route::post('/subscribe/AddSubscribe', [Controller::class, 'AddSubscribe']);
Route::post('/signup', [Controller::class, 'signup'])->name('signup');
Route::post('/login', [Controller::class, 'login'])->name('login');
// other site login or signup
Route::post('/other-site-login', [Controller::class, 'otherSiteLogin'])->name('other-site-login');
Route::post('/other-signup', [Controller::class, 'otherSiteSignup'])->name('other-site-signup');

Route::post('/confirm-code', [Controller::class, 'confirmCode'])->name('confirm-code');
Route::post('/logout', [Controller::class, 'logout'])->name('logout');

// order
Route::get('/order/{slug}/{service_id}', OrderPage::class)->name('order');
// check guard is user panel check if user is logged in
Route::middleware(['auth:mainUsers'])->group(function () {
    
    Route::get('/panel', Dashboard::class);
    Route::get('/panel/invoice', InvoicePage::class);
    Route::get('/panel/invoice/details', [CartController::class, 'getInvoiceDetails'])->name('panel.invoice.details');
    Route::post('/panel/order/cancel', [CartController::class, 'cancelOrder'])->name('panel.order.cancel');
    Route::get('/panel/payment', PaymentPage::class);  
    Route::get('/panel/payment/details', [CartController::class, 'getPaymentDetails'])->name('panel.payment.details');
    Route::get('/panel/profile', ProfilePage::class); 
    Route::get('/panel/business', BusinessPage::class); 
});

// end user panel

// cart
Route::get('/cart', CartPage::class)->name('cart');
Route::post('/addToCart', [CartController::class, 'addToCart'])->name('addToCart');
Route::post('/payCart', [CartController::class, 'payCart'])->name('payCart');
Route::post('/cart/downloadPdf', [CartController::class, 'downloadPdf'])->name('cart.downloadPdf');
Route::post('/deleteCartItem', [CartController::class, 'deleteCartItem'])->name('deleteCartItem');
Route::post('/createCustomDeleteItem', [CartController::class, 'createCustomDeleteItem'])->name('createCustomDeleteItem');
Route::get('/getOrderItems', [CartController::class, 'getOrderItems'])->name('getOrderItems');
Route::post('/createCustomPackageItem', [CartController::class, 'createCustomPackageItem'])->name('createCustomPackageItem');
Route::post('/deleteCustomPackageItem', [CartController::class, 'deleteCustomPackageItem'])->name('deleteCustomPackageItem');
Route::post('/goPayAll', [CartController::class, 'goPayAll'])->name('goPayAll');
Route::post('/goPaySingle', [CartController::class, 'goPaySingle'])->name('goPaySingle');

// check referense api
Route::get('/check-referense/{id}', [UserController::class, 'checkReferenseApi'])->name('check-referense'); 