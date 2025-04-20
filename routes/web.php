<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/user/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/start-work', [App\Http\Controllers\UserController::class, 'startWork'])->name('start.work');
    Route::post('/stop-work', [App\Http\Controllers\UserController::class, 'stopWork'])->name('stop.work');
});

Route::get('/superadmin/dashboard', fn() => view('superadmin.dashboard'))->name('superadmin.dashboard');
//Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
//Route::get('/user/dashboard', fn() => view('user.dashboard'))->name('user.dashboard');



// Route::get('/generate-captcha', function () {
//     return Captcha::create('default');
// })->name('captcha.generate');

Route::post('/verify-captcha', [App\Http\Controllers\CaptchaController::class, 'verifyCaptcha'])->name('captcha.verify');
Route::get('/reload-captcha', [App\Http\Controllers\CaptchaController::class, 'reloadCaptcha'])->name('reload.captcha');
// Route::get('/reload-captcha', function () {
//     return response()->json(['captcha' => captcha_src('default')]);
// })->name('reload.captcha');



