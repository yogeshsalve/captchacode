<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TryCaptchaController;
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
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/superadmin/dashboard', [App\Http\Controllers\SuperAdminController::class, 'supdashboard'])->name('superadmin.dashboard');
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/update-amount/{user}', [App\Http\Controllers\AdminController::class, 'updateAmount']);



    Route::get('/user/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/captcha-stats', [App\Http\Controllers\UserController::class, 'getCaptchaStats'])->name('user.captcha.stats');
    Route::get('/user/trycaptcha-stats', [App\Http\Controllers\UserController::class, 'trygetCaptchaStats'])->name('user.trycaptcha.stats');
    Route::get('/user/earnedSum', [App\Http\Controllers\UserController::class, 'earnedSum'])->name('user.earnedSum');
    Route::post('/start-work', [App\Http\Controllers\UserController::class, 'startWork'])->name('start.work');
    Route::post('/stop-work', [App\Http\Controllers\UserController::class, 'stopWork'])->name('stop.work');

   


    //TRY CAPTCHA
    Route::post('/trycaptcha', [TryCaptchaController::class, 'store'])
     ->name('trycaptcha.store');
});


Route::post('/verify-captcha', [App\Http\Controllers\CaptchaController::class, 'verifyCaptcha'])->name('captcha.verify')->middleware('web');
Route::post('/tryverify-captcha', [App\Http\Controllers\TryCaptchaController::class, 'tryverifyCaptcha'])->name('trycaptcha.verify')->middleware('web');



Route::get('/reload-captcha', function () {
    $user = Auth::user();

    if (!$user) {
        return response()->json(['captcha' => captcha_src('default') . '?' . rand()]);
    }

    $totalCaptchas = DB::table('captcha_logs')
        ->where('user_id', $user->id)
        ->count();

    $captchaType = $totalCaptchas < 500 ? 'easy' : 'default';

    return response()->json(['captcha' => captcha_src($captchaType) . '?' . rand()]);
});

// Terms and Conditions route
Route::get('/terms', function () {
    return view('terms');
})->name('terms');


