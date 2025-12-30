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
   
       // UPDATED: Password check - Now all users use Hash::check for hashed passwords
       if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
           throw ValidationException::withMessages(['password' => 'Invalid credentials.']);
       }
   
       Auth::login($user);
   
       // UPDATED: Dynamic redirect based on user_type dashboard_url
       $dashboardUrl = $user->userType->dashboard_url ?? '/';  // Fallback to home if not set
       return redirect($dashboardUrl);
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