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
        $validator = Validator::make($request->all(), [
            'captcha' => 'required|captcha',
        ]);

        $status = $validator->fails() ? 'incorrect' : 'correct';
    // Store in DB
    CaptchaLog::create([
        'user_id' => Auth::check() ? Auth::id() : null,
        'status' => $status,
    ]);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'CAPTCHA incorrect.']);
        }
    
        return response()->json(['success' => true, 'message' => 'CAPTCHA correct!']);
    }

    // Method to reload captcha
    public function reloadCaptcha()
    {
        [$image, $code] = Captcha::create('default', true);

        return response()->json([
            'image' => $image,
            'code' => $code // ⚠️ Only for testing or to store in hidden field
        ]);
    }
}
