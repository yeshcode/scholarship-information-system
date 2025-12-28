<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    // Add this method if missing - for GET /login (shows login form)
    public function create()
    {
        return view('auth.login');
    }

    // Our custom store() method for POST /login (handles login and redirects)
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('bisu_email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        }

        // Password check: Students use student_id, others use hashed password
        if ($user->hasRole('Student')) {
            if ($request->password !== $user->student_id) {
                throw ValidationException::withMessages(['password' => 'Invalid credentials.']);
            }
        } else {
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages(['password' => 'Invalid credentials.']);
            }
        }

        Auth::login($user);

        // Redirect based on role
        if ($user->hasRole('Super Admin')) {
            return redirect('/admin/dashboard');
        } elseif ($user->hasRole('Scholarship Coordinator')) {
            return redirect('/coordinator/dashboard');
        } elseif ($user->hasRole('Student')) {
            return redirect('/student/dashboard');
        }

        return redirect('/');  // Fallback
    }

    // Logout method (should already be there)
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}