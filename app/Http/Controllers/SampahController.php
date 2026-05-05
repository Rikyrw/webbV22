<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SampahController extends Controller
{
    public function daftar(Request $request)
    {
        $activePage = 'sampah';
        $pageTitle = 'Daftar Sampah';
        $flash = '';
        $flashType = '';

        // Handle delete request
        if ($request->isMethod('post') && $request->filled('action') && $request->input('action') === 'delete') {
            if ($request->filled('id')) {
                $id = (int) $request->input('id');
                
                // CSRF validation
                if (hash_equals(session('_token', ''), $request->input('_token', ''))) {
                    // Soft delete: mark as nonaktif
                    $flash = 'Data sampah berhasil dihapus';
                    $flashType = 'success';
                } else {
                    $flash = 'Token keamanan tidak valid';
                    $flashType = 'error';
                }
            }
        }

        // Check session flash
        if (session()->has('flash_message')) {
            $flash = session('flash_message');
            $flashType = session('flash_type', 'info');
            session()->forget('flash_message');
            session()->forget('flash_type');
        }

        // Dummy data for waste types
        $sampahList = [
            [
                'id_jenis' => 1,
                'nama_jenis' => 'Plastik',
                'harga_per_kg' => 1500,
                'stok_kg' => 250.5,
                'status' => 'aktif',
            ],
            [
                'id_jenis' => 2,
                'nama_jenis' => 'Kertas',
                'harga_per_kg' => 800,
                'stok_kg' => 180.0,
                'status' => 'aktif',
            ],
            [
                'id_jenis' => 3,
                'nama_jenis' => 'Kaca',
                'harga_per_kg' => 2000,
                'stok_kg' => 3.5,
                'status' => 'aktif',
            ],
            [
                'id_jenis' => 4,
                'nama_jenis' => 'Logam',
                'harga_per_kg' => 5000,
                'stok_kg' => 45.0,
                'status' => 'aktif',
            ],
            [
                'id_jenis' => 5,
                'nama_jenis' => 'Organik',
                'harga_per_kg' => 300,
                'stok_kg' => 500.0,
                'status' => 'aktif',
            ],
        ];

        return view('admin.daftar_sampah', compact(
            'activePage',
            'pageTitle',
            'flash',
            'flashType',
            'sampahList'
        ));
    }
}
