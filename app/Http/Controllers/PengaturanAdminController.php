<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengaturanAdminController extends Controller
{
    public function index()
    {
        // Check if user is superadmin
        if (!auth()->check() || auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized access');
        }

        $activePage = 'pengaturan';
        $pageTitle = 'Pengaturan Admin';
        
        // Dummy admin data (in real app, fetch from database/Supabase)
        $admins = [
            [
                'id_admin' => 1,
                'nama_lengkap' => 'Rizky Saputra',
                'email' => 'rizky@mail.com',
                'role' => 'superadmin',
                'username' => 'rizky',
                'status' => 'aktif',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta'
            ],
            [
                'id_admin' => 2,
                'nama_lengkap' => 'Dewi Lestari',
                'email' => 'dewi@mail.com',
                'role' => 'admin',
                'username' => 'dewi',
                'status' => 'aktif',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 5, Jakarta'
            ],
            [
                'id_admin' => 3,
                'nama_lengkap' => 'Bagas Pratama',
                'email' => 'bagas@mail.com',
                'role' => 'operator',
                'username' => 'bagas',
                'status' => 'aktif',
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Gatot Subroto No. 10, Jakarta'
            ],
        ];
        
        return view('admin.pengaturan_admin', compact(
            'activePage',
            'pageTitle',
            'admins'
        ));
    }

    public function store(Request $request)
    {
        // Check if superadmin
        if (!auth()->check() || auth()->user()->role !== 'superadmin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $action = $request->input('action');
        
        if ($action === 'add') {
            // Create new admin
            $validated = $request->validate([
                'username' => 'required|string',
                'nama_lengkap' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string|min:6',
                'role' => 'required|in:operator,admin,superadmin',
                'no_hp' => 'nullable|string',
                'alamat' => 'nullable|string',
            ]);
            
            // In real app: Create in database/Supabase
            return response()->json([
                'status' => 'success',
                'message' => 'Admin berhasil ditambahkan'
            ]);
        } 
        elseif ($action === 'edit') {
            // Update admin
            $validated = $request->validate([
                'id_admin' => 'required|integer',
                'username' => 'required|string',
                'nama_lengkap' => 'required|string',
                'email' => 'required|email',
                'password' => 'nullable|string|min:6',
                'role' => 'required|in:operator,admin,superadmin',
                'status' => 'required|in:aktif,nonaktif',
                'no_hp' => 'nullable|string',
                'alamat' => 'nullable|string',
            ]);
            
            // In real app: Update in database/Supabase
            return response()->json([
                'status' => 'success',
                'message' => 'Admin berhasil diupdate'
            ]);
        }
        elseif ($action === 'delete') {
            // Delete admin
            $id = $request->input('id_admin');
            
            // In real app: Delete from database/Supabase
            return response()->json([
                'status' => 'success',
                'message' => 'Admin berhasil dihapus'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid action'
        ]);
    }
}
