<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\Auth\ForgotPassword;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Auth\SignUp;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Billing;
use App\Http\Livewire\Operational\CheckPrice;
use App\Http\Livewire\Operational\CreateOrder;
use App\Http\Livewire\Operational\CreateRekanan;
use App\Http\Livewire\Operational\DetailOrder;
use App\Http\Livewire\Operational\DetailRekanan;
use App\Http\Livewire\Operational\KalenderMobil;
use App\Http\Livewire\Operational\KalenderMobil2;
use App\Http\Livewire\Operational\KalenderPesanan;
use App\Http\Livewire\Operational\ListVehicle;
use App\Http\Livewire\Operational\Order;
use App\Http\Livewire\Operational\Rekanan;
use App\Http\Livewire\Operational\Driver;
use App\Http\Livewire\Operational\Mobil;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Report\Keuangan;
use App\Http\Livewire\Report\Transaksi;
use App\Http\Livewire\Tables;
use App\Http\Livewire\StaticSignIn;
use App\Http\Livewire\StaticSignUp;
use App\Http\Livewire\Rtl;
use App\Http\Livewire\Users\Form\CreateUser;
use App\Http\Livewire\Users\UserProfile;
use App\Http\Livewire\Users\UserManagement;

use Illuminate\Http\Request;

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

Route::get('/', function() {
    return redirect('/login');
});

Route::get('/sign-up', SignUp::class)->name('sign-up');
Route::get('/login', Login::class)->name('login');

Route::get('/login/forgot-password', ForgotPassword::class)->name('forgot-password');

Route::get('/reset-password/{id}',ResetPassword::class)->name('reset-password')->middleware('signed');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/billing', Billing::class)->name('billing');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/tables', Tables::class)->name('tables');
    Route::get('/static-sign-in', StaticSignIn::class)->name('sign-in');
    Route::get('/static-sign-up', StaticSignUp::class)->name('static-sign-up');
    Route::get('/rtl', Rtl::class)->name('rtl');

    // user management menu
    Route::prefix('user')->group(function () {
        Route::get('/management', UserManagement::class)->name('user.management');
        Route::get('/profile', UserProfile::class)->name('user.profile');
        Route::get('/create', CreateUser::class)->name('user.create');
        Route::get('/edit/{id}', CreateUser::class)->name('user.edit');
    });

    Route::prefix('operational')->group(function () {
        Route::get('/check-price', CheckPrice::class)->name('operational.check-price');
        Route::get('/list-vehicle', ListVehicle::class)->name('operational.list-vehicle');
        Route::get('/detail-order/{id}', DetailOrder::class)->name('operational.detail-order');
        Route::get('/create-order', CreateOrder::class)->name('operational.create-order');
        Route::get('/order', Order::class)->name('operational.order');
        Route::get('/kalender-pesanan', KalenderPesanan::class)->name('operational.kalender-pesanan');
        Route::get('/kalender-mobil', KalenderMobil::class)->name('operational.kalender-mobil');
        Route::get('/kalender-mobil2', KalenderMobil2::class)->name('operational.kalender-mobil2');
        Route::get('/rekanan', Rekanan::class)->name('operational.rekanan');
        Route::get('/create-rekanan', CreateRekanan::class)->name('operational.create-rekanan');
        Route::get('/edit-rekanan/{id}', CreateRekanan::class)->name('operational.edit-rekanan');
        Route::get('/detail-rekanan/{id}', DetailRekanan::class)->name('operational.detail-rekanan');
        Route::get('/driver', Driver::class)->name('operational.driver');
        Route::get('/mobil', Mobil::class)->name('operational.mobil');
    });

    Route::prefix('report')->group(function () {
        Route::get('/keuangan', Keuangan::class)->name('operational.keuangan');
        Route::get('/transaksi', Transaksi::class)->name('operational.transaksi');
    });
});

