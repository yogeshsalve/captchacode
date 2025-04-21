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
    
        $user = Auth::user();
        $totalCaptchas = DB::table('captcha_logs')
            ->where('user_id', $user->id)
            ->count();


        // $earnedSum = DB::table('captcha_logs')
        // ->where('user_id', auth()->id())
        // ->sum('earned');
    
        // Use 'easy' captcha for first 500, then 'complex'
        $captchaType = $totalCaptchas < 500 ? 'easy' : 'default'; // change 'default' to 'complex' if you defined it
    
        $captcha_image = captcha_src($captchaType);
    
        $activityData = DB::table('captcha_logs')
            ->selectRaw('DATE(created_at) as date, 
                        SUM(CASE WHEN status = "correct" THEN 1 ELSE 0 END) as correct_count,
                        SUM(CASE WHEN status = "incorrect" THEN 1 ELSE 0 END) as incorrect_count')
            ->where('user_id', $user->id)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

            $captchaStats = DB::table('captcha_logs')
            ->selectRaw('
                COUNT(*) as total_captchas,
                SUM(CASE WHEN status = "correct" THEN 1 ELSE 0 END) as correct_count,
                SUM(CASE WHEN status = "incorrect" THEN 1 ELSE 0 END) as incorrect_count
            ')
            ->where('user_id', auth()->id())
            ->first();
        
        return view('user.dashboard', [
            'captcha_image' => $captcha_image,
            'activity_data' => $activityData,
            // 'earned_sum' => $earnedSum,
            'captchaStats' => $captchaStats,
        ]);
    }

    public function earnedSum()
    {
        $earnedSumm = DB::table('captcha_logs')
        ->selectRaw('
            SUM(earned) as total_earned
            ')
            ->where('user_id', auth()->id())
            ->first();
    
        return response()->json($earnedSumm);
    }

    public function getCaptchaStats()
{
    $userId = auth()->id();

    $stats = DB::table('captcha_logs')
        ->selectRaw('
            COUNT(*) as total_captchas,
            SUM(CASE WHEN status = "correct" THEN 1 ELSE 0 END) as correct_count,
            SUM(CASE WHEN status = "incorrect" THEN 1 ELSE 0 END) as incorrect_count
        ')
        ->where('user_id', $userId)
        ->first();

    return response()->json($stats);
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
