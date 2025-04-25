<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Trycaptcha;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TryCaptchaController extends Controller
{
   
    public function tryverifyCaptcha(Request $request)
    {


      
        \Log::info('Captcha input:', ['input' => $request->captcha]);

        $validator = Validator::make($request->all(), [
            'captcha' => 'required|captcha',
        ]);

        $status = $validator->fails() ? 'incorrect' : 'correct';
        $earned = $status === 'correct' ? 0.50 : -0.25;
    // Store in DB
    Trycaptcha::create([
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
