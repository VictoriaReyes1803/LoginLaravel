<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\IsVerified;
use App\Http\Middleware\IsActive;

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

// Home Route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Registration Routes
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Activation Routes
Route::get('/activate/{user}', [RegisterController::class, 'activate'])
->name('activate');

// Login Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/verification', function () {
    return view('auth.verification');
})->name('verification');


Route::middleware('jwt.auth')->group(function () {
Route::post('/verification', [LoginController::class, 'verification'])->name('verification');
});
// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware([IsVerified::class])->name('dashboard');

// Resend Activation Email Route
Route::post('resendActivationEmail', [RegisterController::class, 'resendActivationEmail'])
->name('resendActivationEmail');

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])
->name('logout');