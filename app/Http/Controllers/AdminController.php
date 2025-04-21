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

       // Get the user's current amount_paid
       public function editAmount($id)
       {
           $user = User::findOrFail($id);
           return response()->json(['amount_paid' => $user->amount_paid]);
       }
   
      // In UserController.php
public function updateAmount(Request $request, $id)
{
    $user = User::findOrFail($id);
    $user->amount_paid = $request->amount_paid;
    $user->save();

    return response()->json(['message' => 'Amount updated successfully.']);
}
}

