<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.signin');
    }

    /**
     * Handle proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Pengalihan ke /dashboard akan ditangani oleh logika di routes/web.php
            // yang akan kemudian mengalihkan ke dashboard spesifik sesuai peran
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ]);
    }

    /**
     * Tampilkan form registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.signup');
    }

    /**
     * Handle proses registrasi.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Secara opsional, login user setelah registrasi
        Auth::login($user);

        // Penting: Setelah registrasi, Anda mungkin ingin menetapkan peran default
        // Misalnya, setiap user baru yang mendaftar adalah 'pasien' secara default.
        // Anda perlu memastikan peran 'pasien' sudah ada di seeder Anda.
        // $patientRole = Role::where('name', 'pasien')->first();
        // if ($patientRole) {
        //     $user->assignRole($patientRole);
        // } else {
        //     // Handle jika peran 'pasien' tidak ditemukan
        //     Log::warning('Role "pasien" not found during user registration.');
        // }

        return redirect('/dashboard')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Handle proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}
