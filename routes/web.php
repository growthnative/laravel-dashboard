<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\StripePaymentController;
use App\Http\Controllers\Admin\PaymentSettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Home route
Route::get('/', function () {
    return view('auth.login');
});

//Auth::routes();
Auth::routes([
    'register' => false,
    'verify' => true
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Auth routes with prefix
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified']], function () {

    //Admin controller routes group
    Route::controller(App\Http\Controllers\Admin\AdminController::class)->group(function () {
        Route::get('/welcome', 'welcome')->name('welcome');
        Route::get('/dashboard', 'dashboard')->name('dashboard');

        // resource controllers
        Route::resources([
            'users' => App\Http\Controllers\Admin\AdminController::class,
        ]);
    });    
});

Route::post('post-login', [LoginController::class, 'postLogin'])->name('login.post'); 

// Route for social login with google
Route::get('auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('callback/google', [SocialController::class, 'handleCallback']);

// Route for social login with facebook
Route::get('/facebook', [SocialController::class, 'redirectToFacebook']);
Route::get('/callback', [SocialController::class, 'handleFacebookCallback']);

// Route for profile section
Route::resource('profile', ProfileController::class)->middleware('auth');

// Change Password
Route::get('change-password', [ChangePasswordController::class,'changePassword'])->name('change-password')->middleware('auth');
Route::Post('change-password', [ChangePasswordController::class,'newPassword'])->name('change-password')->middleware('auth');

// Route for roles 
Route::resource('roles', RoleController::class)->middleware('auth');

// Route for User
Route::resource('users', UserController::class)->middleware('auth');

// set password
Route::get('set-password/{id}', [UserController::class,'setPassword'])->name('setPassword');
Route::post('set-password', [UserController::class,'setNewPassword'])->name('setPassword');

// Resend Email link
Route::get('resend-email/{id}', [UserController::class,'resendEmail'])->name('resendEmail');

Route::post('updateStatus', [UserController::class, 'updateStatus'])->name('updateStatus')->middleware('auth');

// Route for Permission
Route::resource('permission', PermissionController::class)->middleware('auth');

Route::get('account/verify/{token}', [UserController::class, 'verifyAccount'])->name('user.verify'); 

// Route for stripe
Route::get('stripeDetails', [StripePaymentController::class,'stripeDetails'])->name('stripeDetails')->middleware('auth');
Route::get('stripe',[StripePaymentController::class,'paymentStripe'])->name('paymentstripe')->middleware('auth');
Route::post('make/payment',[StripePaymentController::class,'makePayment'])->name('make.payment')->middleware('auth');
Route::post('returnPayment', [StripePaymentController::class,'returnPayment'])->name('returnPayment')->middleware('auth');
Route::get('showPayment/{id}', [StripePaymentController::class,'showPayment'])->name('showPayment')->middleware('auth');
Route::get('reterivePayment', [StripePaymentController::class,'reterivePayment'])->name('reterivePayment')->middleware('auth');
Route::get('reterivePayment', [StripePaymentController::class,'reterivePayment'])->name('reterivePayment')->middleware('auth');
Route::post('syncRefundPayment', [StripePaymentController::class,'syncRefundPayment'])->name('syncRefundPayment')->middleware('auth');


// Route for stripe-payment setting
Route::resource('payment-setting', PaymentSettingController::class)->middleware('auth');
Route::post('updatePaymentStatus', [PaymentSettingController::class, 'updatePaymentStatus'])->name('updatePaymentStatus')->middleware('auth');

// Route::post('add-money-stripe',[StripePaymentController::class,'postPaymentStripe'])->name('addmoney.stripe');


//fallback route if route is not found
Route::fallback(function () {
    return view('404');
});

