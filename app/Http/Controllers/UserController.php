<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the dashboard based on user type.
     */
    public function dashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // Dynamic dashboard based on user_type_id
        switch ($user->user_type_id) {
            case 1: // Admin
                return view('admin.dashboard');

            case 2: // Staff / Scholarship Coordinator
                return view('staff.dashboard');

            case 3: // Student
            default:
                return view('students.dashboard');
        }
    }
}
