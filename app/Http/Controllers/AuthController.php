<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controller untuk menangani autentikasi pengguna
 * Menggunakan prinsip clean code dengan fungsi yang fokus dan mudah dipahami
 */
class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Memproses login pengguna dengan validasi email dan password
     * Mendukung upgrade otomatis dari MD5 ke bcrypt untuk keamanan
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input dengan aturan yang jelas
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Normalisasi email ke huruf kecil untuk konsistensi
        $credentials['email'] = strtolower($credentials['email']);

        // Cari pengguna berdasarkan email
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Pengguna tidak ditemukan'])->onlyInput('email');
        }

        // Verifikasi password dengan dukungan legacy MD5
        if (!$this->verifikasiPassword($user, $credentials['password'])) {
            return back()->withErrors(['email' => 'Password salah'])->onlyInput('email');
        }

        // Login berhasil - redirect sesuai role
        return $this->loginBerhasilRedirect($user, $request);
    }

    /**
     * Memverifikasi password dengan dukungan MD5 legacy dan bcrypt
     * Upgrade otomatis dari MD5 ke bcrypt untuk keamanan
     * 
     * @param User $user
     * @param string $password
     * @return bool
     */
    private function verifikasiPassword(User $user, string $password): bool
    {
        // Cek apakah password menggunakan MD5 (32 karakter hex)
        if (strlen($user->password) === 32 && ctype_xdigit($user->password)) {
            if (md5($password) === $user->password) {
                // Upgrade ke bcrypt untuk keamanan yang lebih baik
                $user->password = Hash::make($password);
                $user->save();
                return true;
            }
        } else {
            // Password sudah menggunakan bcrypt
            return Hash::check($password, $user->password);
        }

        return false;
    }

    /**
     * Menangani redirect setelah login berhasil berdasarkan role
     * 
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginBerhasilRedirect(User $user, Request $request)
    {
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Redirect berdasarkan role pengguna
        if ($user->hasRole('gudang')) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->hasRole('dapur')) {
            return redirect()->route('client.dashboard');
        }

        // Fallback redirect
        return redirect('/');
    }
    
    /**
     * Menangani logout pengguna dengan membersihkan sesi
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Menampilkan dashboard admin untuk role gudang
     * 
     * @return \Illuminate\View\View
     */
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
}
