<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
}

