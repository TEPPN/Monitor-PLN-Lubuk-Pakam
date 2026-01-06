<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
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
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/map', [App\Http\Controllers\RecapController::class, 'map'])->name('map');

    Route::get('/recaps', [App\Http\Controllers\RecapController::class, 'index'])->name('recap.index');
    Route::get('/recaps/create', [App\Http\Controllers\RecapController::class, 'create'])->name('recap.create');
    Route::post('/recaps', [App\Http\Controllers\RecapController::class, 'store'])->name('recap.store');
    Route::get('/recaps/{recap}/edit', [App\Http\Controllers\RecapController::class, 'edit'])->name('recap.edit');
    Route::put('/recaps/{recap}', [App\Http\Controllers\RecapController::class, 'update'])->name('recap.update');
    Route::delete('/recaps/{recap}', [App\Http\Controllers\RecapController::class, 'destroy'])->name('recap.destroy');
    Route::get('/company', [App\Http\Controllers\CompanyController::class, 'index'])->name('company.index');
    Route::get('/company/create', [App\Http\Controllers\CompanyController::class, 'create'])->name('company.create');
    Route::post('/company', [App\Http\Controllers\CompanyController::class, 'store'])->name('company.store');

    Route::get('/contract', [App\Http\Controllers\ContractController::class, 'index'])->name('contract.index');
    Route::get('/contract/create', [App\Http\Controllers\ContractController::class, 'create'])->name('contract.create');
    Route::post('/contract', [App\Http\Controllers\ContractController::class, 'store'])->name('contract.store');
    Route::get('/contract/{contract}/edit', [App\Http\Controllers\ContractController::class, 'edit'])->name('contract.edit');
    Route::put('/contract/{contract}', [App\Http\Controllers\ContractController::class, 'update'])->name('contract.update');
    Route::delete('/contract/{contract}', [App\Http\Controllers\ContractController::class, 'destroy'])->name('contract.destroy');
    
    Route::get('/log', [App\Http\Controllers\LogController::class, 'index'])->name('log.index');
    Route::get('/log/download', [App\Http\Controllers\LogController::class, 'download'])->name('log.download');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth:admin', 'master'])->group(function () {
        Route::get('/', [AdminProfileController::class, 'show'])->name('profile');
        Route::resource('users', AdminUserController::class)->except(['edit', 'update']);
    });
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');