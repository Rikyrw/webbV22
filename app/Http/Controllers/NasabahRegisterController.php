<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NasabahRegisterController extends Controller
{
    public function showRegister()
    {
        return view('nasabah.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:8',
            'konfirmasi_password' => 'required|string|max:8',
            'alamat' => 'nullable|string',
            'no_hp' => 'required|string|max:20',
        ]);

        // Validasi: Konfirmasi password
        if ($validated['password'] !== $validated['konfirmasi_password']) {
            return back()->withInput()->withErrors(['password' => 'Password dan konfirmasi password tidak sama!']);
        }

        // Validasi: Panjang password
        if (strlen($validated['password']) > 8) {
            return back()->withInput()->withErrors(['password' => 'Password maksimal 8 karakter!']);
        }

        try {
            // Check existing user
            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');
            
            $query = "nasabah?select=id_nasabah&or=(email.eq." . urlencode($validated['email']) . ",username.eq." . urlencode($validated['username']) . ")";
            
            $response = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
            ])->get($supabaseUrl . '/rest/v1/' . $query);

            $existing = $response->json();

            if (is_array($existing) && count($existing) > 0) {
                return back()->withInput()->withErrors(['email' => 'Email atau username sudah terdaftar!']);
            }

            // Generate salt dan hash password
            $salt = $this->generateSalt();
            $password_hash = $this->hashPasswordLikeAndroid($validated['password'], $salt);
            $konfirmasi_hash = $this->hashPasswordLikeAndroid($validated['konfirmasi_password'], $salt);

            // Create new user
            $newUser = [
                'nama_nasabah' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => $password_hash,
                'konfirmasi_password' => $konfirmasi_hash,
                'salt' => $salt,
                'alamat' => $validated['alamat'] ?? '',
                'no_hp' => $validated['no_hp'],
                'tanggal_daftar' => now()->format('Y-m-d'),
                'status_akun' => 'aktif',
                'saldo' => 0,
                'metode_kontak' => 'whatsapp'
            ];

            $insertResponse = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation',
            ])->post($supabaseUrl . '/rest/v1/nasabah', $newUser);

            $result = $insertResponse->json();

            if ($insertResponse->successful() && is_array($result) && count($result) > 0) {
                return redirect()->route('nasabah.login')->with('success', 'Pendaftaran berhasil! Silakan login.');
            } else {
                return back()->withInput()->withErrors(['email' => 'Gagal mendaftar. Coba lagi.']);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['email' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Hash password dengan metode Android (SHA256 + Salt + Base64)
     */
    private function hashPasswordLikeAndroid($password, $salt)
    {
        $saltBinary = base64_decode($salt);
        $hashed = hash('sha256', $saltBinary . $password, true);
        return base64_encode($hashed);
    }

    /**
     * Generate random salt
     */
    private function generateSalt()
    {
        $salt = random_bytes(16);
        return base64_encode($salt);
    }
}
