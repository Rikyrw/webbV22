<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $activePage = 'transaksi';
        $pageTitle = 'Transaksi';
        $tab = $request->get('tab', 'setor'); // Default tab: setor

        // Dummy data for setor requests
        $setorRequests = [
            [
                'id_transaksi' => 1,
                'id_nasabah' => 101,
                'nama_nasabah' => 'Siti Rahma',
                'total_berat' => 125.50,
                'total_nilai' => 1500000,
                'jenis' => 'Plastik, Kertas',
                'tanggal_setor' => '2024-04-18 10:30:00',
                'status' => 'menunggu',
            ],
            [
                'id_transaksi' => 2,
                'id_nasabah' => 102,
                'nama_nasabah' => 'Budi Santoso',
                'total_berat' => 85.75,
                'total_nilai' => 1000000,
                'jenis' => 'Kaca, Logam',
                'tanggal_setor' => '2024-04-18 09:15:00',
                'status' => 'menunggu',
            ],
            [
                'id_transaksi' => 3,
                'id_nasabah' => 103,
                'nama_nasabah' => 'Rahmawati',
                'total_berat' => 200.00,
                'total_nilai' => 2500000,
                'jenis' => 'Plastik',
                'tanggal_setor' => '2024-04-17 14:45:00',
                'status' => 'approved',
            ],
        ];

        // Dummy data for penarikan (withdrawal) requests
        $penarikanRequests = [
            [
                'id_penukaran' => 1,
                'id_nasabah' => 101,
                'nama_nasabah' => 'Siti Rahma',
                'jenis_penukaran' => 'Transfer Bank',
                'nominal' => 500000,
                'deskripsi' => 'Permintaan penarikan saldo',
                'tanggal_pengajuan' => '2024-04-18 11:00:00',
                'status' => 'menunggu',
            ],
            [
                'id_penukaran' => 2,
                'id_nasabah' => 102,
                'nama_nasabah' => 'Budi Santoso',
                'jenis_penukaran' => 'Tunai',
                'nominal' => 250000,
                'deskripsi' => 'Penarikan tunai di kantor',
                'tanggal_pengajuan' => '2024-04-18 08:30:00',
                'status' => 'menunggu',
            ],
            [
                'id_penukaran' => 3,
                'id_nasabah' => 103,
                'nama_nasabah' => 'Rahmawati',
                'jenis_penukaran' => 'Transfer Bank',
                'nominal' => 750000,
                'deskripsi' => 'Penarikan rutin',
                'tanggal_pengajuan' => '2024-04-17 16:20:00',
                'status' => 'approved',
            ],
        ];

        // Combined history
        $history = [];
        foreach ($setorRequests as $s) {
            $history[] = [
                'type' => 'setor',
                'waktu' => $s['tanggal_setor'],
                'nama' => $s['nama_nasabah'],
                'jumlah' => $s['total_nilai'],
                'keterangan' => 'Setor Sampah: ' . $s['jenis'],
            ];
        }
        foreach ($penarikanRequests as $p) {
            $history[] = [
                'type' => 'penarikan',
                'waktu' => $p['tanggal_pengajuan'],
                'nama' => $p['nama_nasabah'],
                'jumlah' => $p['nominal'],
                'keterangan' => $p['jenis_penukaran'] . ' - ' . $p['deskripsi'],
            ];
        }

        // Sort by date descending
        usort($history, function ($a, $b) {
            return strtotime($b['waktu']) - strtotime($a['waktu']);
        });

        return view('admin.transaksi', compact(
            'activePage',
            'pageTitle',
            'tab',
            'setorRequests',
            'penarikanRequests',
            'history'
        ));
    }
}
