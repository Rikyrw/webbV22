<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NasabahController extends Controller
{
    public function daftar(Request $request)
    {
        $activePage = 'nasabah';
        $pageTitle = 'Daftar Nasabah';
        $flash = '';
        $nasabahs = [];

        // Handle POST action: approve or reject nasabah
        if ($request->isMethod('post') && $request->filled('action') && $request->filled('id_nasabah')) {
            $id = (int) $request->input('id_nasabah');
            $action = $request->input('action'); // expected: aktifkan | tolak

            // Validate CSRF token
            if (!hash_equals(session('csrf_token', ''), $request->input('csrf_token', ''))) {
                $flash = 'Token keamanan tidak valid.';
            } else {
                if ($action === 'aktifkan') {
                    $newStatus = 'aktif';
                } elseif ($action === 'tolak') {
                    $newStatus = 'nonaktif';
                } else {
                    $newStatus = null;
                }

                if ($newStatus) {
                    // Use Supabase REST to update nasabah status
                    $flash = 'Status nasabah berhasil diperbarui.';
                } else {
                    $flash = 'Aksi tidak dikenali.';
                }
            }
        }

        // Check session flash
        if (session()->has('flash_nasabah')) {
            $flash = session('flash_nasabah');
            session()->forget('flash_nasabah');
        }

        // Dummy data for now (replace with database calls later)
        $nasabahs = [
            [
                'id_nasabah' => 1,
                'nama_nasabah' => 'Siti Rahma',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'no_hp' => '08123456789',
                'saldo' => 1200000,
                'status_akun' => 'aktif',
                'tanggal_daftar' => '2024-01-15'
            ],
            [
                'id_nasabah' => 2,
                'nama_nasabah' => 'Budi Santoso',
                'alamat' => 'Jl. Sudirman No. 456, Bandung',
                'no_hp' => '08234567890',
                'saldo' => 980000,
                'status_akun' => 'menunggu',
                'tanggal_daftar' => '2024-02-20'
            ],
            [
                'id_nasabah' => 3,
                'nama_nasabah' => 'Rahmawati',
                'alamat' => 'Jl. Ahmad Yani No. 789, Surabaya',
                'no_hp' => '08345678901',
                'saldo' => 720000,
                'status_akun' => 'aktif',
                'tanggal_daftar' => '2024-03-10'
            ],
            [
                'id_nasabah' => 4,
                'nama_nasabah' => 'Ahmad Wijaya',
                'alamat' => 'Jl. Gatot Subroto No. 321, Medan',
                'no_hp' => '08456789012',
                'saldo' => 550000,
                'status_akun' => 'menunggu',
                'tanggal_daftar' => '2024-04-05'
            ],
        ];

        return view('admin.daftar_nasabah', compact(
            'activePage',
            'pageTitle',
            'flash',
            'nasabahs'
        ));
    }
}
