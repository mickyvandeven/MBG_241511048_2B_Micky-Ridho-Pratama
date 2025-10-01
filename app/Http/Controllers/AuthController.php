<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Normalisasi
        $credentials['email'] = strtolower($credentials['email']);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Arahkan sesuai role
            if (auth()->user()->hasRole('gudang')) return redirect()->route('admin.dashboard');
            if (auth()->user()->hasRole('dapur')) return redirect()->route('client.dashboard');
            return redirect('/'); // fallback
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email');
    }
    
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

}
