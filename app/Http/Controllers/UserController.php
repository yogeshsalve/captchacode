<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role != 2) {
            abort(403, 'Unauthorized');
        }
      
        $captcha_image = captcha_src('default'); // Just the image URL
        $userId = auth()->id();
        $activityData = DB::table('captcha_logs')
        ->selectRaw('DATE(created_at) as date, 
                     SUM(CASE WHEN status = "correct" THEN 1 ELSE 0 END) as correct_count,
                     SUM(CASE WHEN status = "incorrect" THEN 1 ELSE 0 END) as incorrect_count')
        ->where('user_id', $userId)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();
       


        return view('user.dashboard', [
            'captcha_image' => $captcha_image,
            'activity_data' => $activityData
        ]);
    }

    public function startWork()
    {
        $user = Auth::user();
         $user->work_started = 'yes';
         $user->save();
         
         return redirect()->back();
    }

    public function stopWork()
    {
        $user = Auth::user();
        $user->work_started = 'no';
        $user->save();
      
        return redirect()->back();
    }
}
