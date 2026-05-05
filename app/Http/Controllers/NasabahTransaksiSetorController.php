<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NasabahTransaksiSetorController extends Controller
{
    public function index(Request $request)
    {
        $activePage = 'setor';

        // Dummy user data
        $user = [
            'id_nasabah' => 1,
            'nama_nasabah' => 'Ridho Pratama',
            'alamat' => 'Jl. Merdeka No. 42, Jakarta Selatan',
            'saldo' => 250000
        ];

        // Dummy jenis sampah
        $waste_types = [
            [
                'id_jenis' => 1,
                'nama_jenis' => 'Kertas',
                'harga_per_kg' => 1500
            ],
            [
                'id_jenis' => 2,
                'nama_jenis' => 'Plastik',
                'harga_per_kg' => 2000
            ],
            [
                'id_jenis' => 3,
                'nama_jenis' => 'Logam',
                'harga_per_kg' => 5000
            ],
            [
                'id_jenis' => 4,
                'nama_jenis' => 'Kaca',
                'harga_per_kg' => 1000
            ]
        ];

        session([
            'nama_nasabah' => $user['nama_nasabah'],
            'saldo' => $user['saldo'],
            'alamat' => $user['alamat']
        ]);

        // Handle profile update
        $profile_success = null;
        $profile_error = null;
        if ($request->isMethod('post') && $request->has('update_profile')) {
            $user['nama_nasabah'] = $request->input('nama_nasabah');
            $user['alamat'] = $request->input('alamat');
            session([
                'nama_nasabah' => $user['nama_nasabah'],
                'alamat' => $user['alamat']
            ]);
            $profile_success = "Profil berhasil diperbarui!";
        }

        // Handle setor transaction submission
        $success = null;
        $error = null;
        if ($request->isMethod('post') && $request->has('submit_transaction')) {
            $waste_items = $request->input('waste_items', []);
            
            if (empty($waste_items)) {
                $error = "Tambahkan minimal 1 item sebelum mengajukan setor";
            } else {
                // Simulate successful transaction
                $total_berat = $request->input('total_berat', 0);
                $total_nilai = $request->input('total_nilai', 0);
                
                // In real implementation, this would be saved to database via Supabase
                $success = "Transaksi setor sampah berhasil diajukan! Status: Menunggu persetujuan admin.";
                
                // Update user saldo (dummy)
                $user['saldo'] = $user['saldo'] + (float)$total_nilai;
                session(['saldo' => $user['saldo']]);
            }
        }

        return view('nasabah.transaksi_setor', [
            'activePage' => $activePage,
            'user' => $user,
            'waste_types' => $waste_types,
            'profile_success' => $profile_success,
            'profile_error' => $profile_error,
            'success' => $success,
            'error' => $error
        ]);
    }
}
