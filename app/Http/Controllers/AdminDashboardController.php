<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $activePage = 'dashboard';
        $pageTitle = 'Dashboard';
        
        // No DB dependency and no login required: return sample/dummy data for the dashboard
        $nasabahCount = 1287;
        $topNasabah = [
            ['nama_nasabah' => 'Siti Rahma', 'saldo' => 1200000],
            ['nama_nasabah' => 'Budi Santoso', 'saldo' => 980000],
            ['nama_nasabah' => 'Rahmawati', 'saldo' => 720000],
        ];
        $totalSaldo = 3100000;
        $totalSampahToday = 256.45;
        $pendapatanThisMonth = 12500000;

        // sample charts (last 7 days)
        $lineLabels = [];
        $lineData = [];
        for ($d = 6; $d >= 0; $d--) {
            $day = date('Y-m-d', strtotime("-{$d} days"));
            $lineLabels[] = date('d M', strtotime($day));
            // simple synthetic pattern
            $lineData[] = rand(20, 60);
        }

        // sample pie data
        $pieLabels = ['Plastik','Kertas','Kaca','Logam','Organik'];
        $pieData = [120, 80, 40, 30, 60];

        return view('admin.dashboard', compact(
            'activePage', 'pageTitle', 'nasabahCount', 'topNasabah', 'totalSaldo', 'totalSampahToday', 'pendapatanThisMonth', 'lineLabels', 'lineData', 'pieLabels', 'pieData'
        ));
    }
}
