<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CaptchaLog;
use Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role != 1) {
            abort(403, 'Unauthorized');
        }

        $users = User::where('role', 2)->get(); // Get all users with role 2 (normal users)

        return view('admin.dashboard', compact('users'));
    }

    public function updateAmount(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount_received' => 'required|numeric|min:0'
        ]);
    
        $user->amount_received = $validated['amount_received'];
        $user->save();

        // Log the captcha entry
        CaptchaLog::create([
            'user_id' => $user->id,
            'status' => 'correct',
            'earned' => $validated['amount_received'],
        ]);
    
        return response()->json(['message' => 'Amount updated successfully.']);
    }
}

