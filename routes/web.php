<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\UserController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/map', function () {
        return view('pages.map');
    });
    Route::get('/company', [App\Http\Controllers\CompanyController::class, 'index'])->name('company.index');
    Route::get('/company/create', [App\Http\Controllers\CompanyController::class, 'create'])->name('company.create');
    Route::post('/company', [App\Http\Controllers\CompanyController::class, 'store'])->name('company.store');
    Route::get('/contract', function () {
        return view('pages.contract');
    });
    Route::get('/log', function () {
        return view('pages.log');
    });
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');