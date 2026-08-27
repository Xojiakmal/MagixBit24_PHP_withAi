<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function show()
    {
        $superadminExists = User::where('is_superadmin', true)->exists();
        return view('admin.local_login', compact('superadminExists'));
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $superadminExists = User::where('is_superadmin', true)->exists();
        $user = Auth::user();

        if (!$superadminExists) {
            // Setup flow
            $user->update([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_superadmin' => true,
            ]);

            session(['admin_verified' => true]);
            return redirect()->route('superadmin.index')->with('success', 'Siz muvaffaqiyatli Superadmin sifatida ro\'yxatdan o\'tdingiz!');
        }

        // Verification flow
        if (!$user->is_superadmin) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Sizda Superadmin huquqi yo\'q!');
        }

        // If email and password match
        if ($user->email === $request->email && Hash::check($request->password, $user->password)) {
            session(['admin_verified' => true]);
            return redirect()->route('superadmin.index')->with('success', 'Tizimga muvaffaqiyatli kirdingiz!');
        }

        return redirect()->route('admin.local.login')->with('error', 'Noto\'g\'ri email yoki parol kiritdingiz!');
    }
}
