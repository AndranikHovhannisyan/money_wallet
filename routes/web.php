<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\WalletController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('auth/google', [GoogleController::class,'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class,'handleGoogleCallback']);

Route::resource('wallet', 'WalletController', ['except' => ['show', 'delete']]);

Route::get('wallet/{wallet}/destroy', [WalletController::class, 'destroy'])->name('wallet.destroy');

Route::prefix('record/{wallet}')->name('record.')->group(function () {
    Route::get('/', [RecordController::class, 'index'])->name('index');
    Route::post('/', [RecordController::class, 'store'])->name('store');
});

