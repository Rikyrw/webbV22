<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NasabahProfilController extends Controller
{
    public function index(Request $request)
    {
        $activePage = 'profil';

        // Dummy user data
        $user = [
            'id_nasabah' => 1,
            'nama_nasabah' => 'Ridho Pratama',
            'username' => 'ridhopratama',
            'email' => 'ridho@example.com',
            'alamat' => 'Jl. Merdeka No. 42, Jakarta Selatan',
            'no_hp' => '082123456789',
            'saldo' => 250000,
            'tanggal_daftar' => '2024-01-10'
        ];

        session([
            'nama_nasabah' => $user['nama_nasabah'],
            'saldo' => $user['saldo'],
            'username' => $user['username'],
            'email' => $user['email'],
            'alamat' => $user['alamat'],
            'no_hp' => $user['no_hp'],
        ]);

        // Generate initials
        $initials = $this->getInitials($user['nama_nasabah'] ?? 'User');

        return view('nasabah.profil', compact(
            'activePage',
            'user',
            'initials'
        ));
    }

    public function edit(Request $request)
    {
        $activePage = 'profil';

        // Dummy user data
        $user = [
            'id_nasabah' => 1,
            'nama_nasabah' => 'Ridho Pratama',
            'username' => 'ridhopratama',
            'email' => 'ridho@example.com',
            'alamat' => 'Jl. Merdeka No. 42, Jakarta Selatan',
            'no_hp' => '082123456789',
            'saldo' => 250000,
            'tanggal_daftar' => '2024-01-10'
        ];

        return view('nasabah.ubah-profil', compact(
            'activePage',
            'user'
        ));
    }

    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_nasabah' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ], [
            'nama_nasabah.required' => 'Nama lengkap harus diisi',
            'nama_nasabah.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter',
            'no_hp.max' => 'Nomor handphone tidak boleh lebih dari 20 karakter',
            'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter',
        ]);

        // TODO: Simpan data ke database
        // User::where('id', auth()->id())->update($validated);

        // Update session
        session([
            'nama_nasabah' => $validated['nama_nasabah'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? '',
            'alamat' => $validated['alamat'] ?? '',
        ]);

        return redirect()->route('nasabah.profil')->with('success', 'Profil berhasil diperbarui');
    }

    private function getInitials($name)
    {
        $initials = '';
        $words = explode(' ', $name);
        foreach ($words as $word) {
            if (trim($word) !== '') {
                $initials .= strtoupper(substr($word, 0, 1));
                if (strlen($initials) >= 2) break;
            }
        }
        // Jika hanya 1 huruf, tambahkan huruf pertama lagi
        if (strlen($initials) == 1 && isset($words[0])) {
            $initials .= strtoupper(substr($words[0], 1, 1));
        }
        return $initials;
    }
}
