<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim((string) $request->email);

        $user = User::where('email', $input)
            ->orWhere('nama_lengkap', $input)
            ->first();

        // Support default username 'admin' if requested
        if (! $user && strtolower($input) === 'admin') {
            $user = User::first();
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Username/Email atau password salah.'], 422);
            }
            return back()->withErrors(['email' => 'Username/Email atau password salah.'])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        $redirectTo = $request->input('redirect_to');
        if ($redirectTo && ! str_starts_with($redirectTo, '//') && str_starts_with($redirectTo, '/')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'redirect' => $redirectTo]);
            }
            return redirect($redirectTo)->with('success', 'Selamat datang, '.$user->nama_lengkap.'!');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        }

        return redirect()->route('dashboard')->with('success', 'Selamat datang, '.$user->nama_lengkap.'!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
