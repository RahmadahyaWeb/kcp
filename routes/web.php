<?php

use App\Http\Controllers\AopController;
use App\Http\Controllers\AopReceiptController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DksController;
use App\Http\Controllers\MasterTokoController;
use App\Http\Controllers\NonAopController;
use App\Http\Controllers\ReportDKSController;
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

Route::middleware(['auth', 'check.online', 'auth.session'])->group(function () {
    // DASHBOARD
    Route::get('/', function () {
        return view('welcome');
    })->name('dashboard');

    // DKS-SCAN
    Route::get('dks-scan/{kd_toko?}', [DksController::class, 'index'])->name('dks.scan');
    Route::post('dks-scan/store/{kd_toko}', [DksController::class, 'store'])->name('dks.store');

    // REPORT DKS
    Route::get('report/dks', [ReportDKSController::class, 'index'])->name('report.dks');

    // EXPORT DKS
    Route::post('report/dks/export', [ReportDKSController::class, 'export'])->name('report-dks.export');

    // HELP CENTER
    Route::get('help-center', function () {
        return view('help');
    })->name('help-center');

    // MASTER TOKO
    Route::get('master-toko', [MasterTokoController::class, 'index'])->name('master-toko.index');
    Route::get('master-toko/create', [MasterTokoController::class, 'create'])->name('master-toko.create');
    Route::post('master-toko/store', [MasterTokoController::class, 'store'])->name('master-toko.store');
    Route::get('master-toko/edit/{kd_toko}', [MasterTokoController::class, 'edit'])->name('master-toko.edit');
    Route::put('master-toko/update/{kd_toko}', [MasterTokoController::class, 'update'])->name('master-toko.update');
    Route::delete('master-toko/destroy/{kd_toko}', [MasterTokoController::class, 'destroy'])->name('master-toko.destroy');

    // AOP UPLOAD FILE
    Route::get('/aop', [AopController::class, 'indexUpload'])->name('aop.index');

    // AOP DETAIL
    Route::get('/aop/detail/{invoiceAop}', [AopController::class, 'detail'])->name('aop.detail');

    // AOP Final
    Route::get('/aop/final', [AopController::class, 'final'])->name('aop.final');

    // NON AOP
    Route::get('/non-aop', [NonAopController::class, 'index'])->name('non-aop.index');

    // CREATE NON AOP
    Route::get('/non-aop/create', [NonAopController::class, 'create'])->name('non-aop.create');

    // DETAIL NON AOP
    Route::get('/non-aop/detail/{invoiceNon}', [NonAopController::class, 'detail'])->name('non-aop.detail');

    // AOP GR
    Route::get('/gr/aop', [AopReceiptController::class, 'index'])->name('aop-gr.index');

    // AOP GR DETAIL
    Route::get('/gr/aop/{spb}', [AopReceiptController::class, 'detail'])->name('aop-gr.detail');

    // LOGOUT
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
