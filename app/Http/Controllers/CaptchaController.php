<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mews\Captcha\Facades\Captcha;
use App\Models\CaptchaLog;
use App\Models\User;

class CaptchaController extends Controller
{
    
     

    // Method to verify the entered captcha
    public function verifyCaptcha(Request $request)
    {


        \Log::info('Captcha input:', ['input' => $request->captcha]);

        $validator = Validator::make($request->all(), [
            'captcha' => 'required|captcha',
        ]);

        $status = $validator->fails() ? 'incorrect' : 'correct';
        $earned = $status === 'correct' ? 0.50 : -0.25;
    // Store in DB
    CaptchaLog::create([
        'user_id' => Auth::check() ? Auth::id() : null,
        'status' => $status,
        'earned' => $earned,
    ]);
    
        if ($status == "incorrect") {
            return response()->json(['success' => false, 'message' => 'INCORRECT CAPTCHA']);
        }else{
    
        return response()->json(['success' => true, 'message' => 'CORRECT CAPTCHA']);
        }
    }

   
}
