<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role != 2) {
            abort(403, 'Unauthorized');
        }
        $captcha_image = captcha_src('default'); // Just the image URL
        return view('user.dashboard', [
            'captcha_image' => $captcha_image,
        ]);
    }

    public function startWork()
    {
        $user = Auth::user();
         $user->work_started = 'yes';
         $user->save();

         return redirect()->back();
    }
}
