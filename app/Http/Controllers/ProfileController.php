<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya user yang sudah login yang bisa akses
        $this->middleware('auth');
    }

    // Menampilkan halaman profil
    public function index()
    {
        $user = auth()->user();
        $activeMenu = 'profile';
        $breadcrumb = (object)[
            'title' => 'Profile',
            'list' => ['Home', 'Profile']
        ];

        return view('profile.index', compact('user', 'activeMenu', 'breadcrumb'));
    }

    // Mengubah foto profil
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $user = auth()->user();

        // Pastikan user terautentikasi dan kolom 'foto' tersedia
        if (!$user) {
            return redirect()->route('login')->withErrors('Silakan login terlebih dahulu.');
        }

        try {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
                Storage::delete('public/foto/' . $user->foto);
            }

            // Upload foto baru
            $path = $request->file('foto')->store('public/foto');
            $namaFile = basename($path);

            // Simpan nama file ke database
            $user->foto = $namaFile;
            $user->save();

            return back()->with('success', 'Foto profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengubah foto: ' . $e->getMessage());
        }
    }
}
