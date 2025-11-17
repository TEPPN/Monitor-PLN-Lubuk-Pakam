<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class LoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an admin authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        // Attempt to log in as a 'master' user on the 'admin' guard
        if (Auth::guard('admin')->attempt($credentials + ['account_type' => 'master'])) {
            $request->session()->regenerate();

            Log::create([
                'user_id' => Auth::guard('admin')->id(),
                'action_type' => 'login',
                'action_detail' => 'Admin user logged in successfully.'
            ]);

            return redirect()->intended(route('admin.users.index'));
        }

        return back()->withErrors([
            'name' => 'The provided credentials do not match our records or you are not a master user.',
        ])->onlyInput('name');
    }

    /**
     * Log the admin user out.
     */
    public function logout(Request $request)
    {
        Log::create([
            'user_id' => Auth::guard('admin')->id(),
            'action_type' => 'logout',
            'action_detail' => 'Admin user logged out.'
        ]);

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
