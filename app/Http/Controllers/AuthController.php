<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login() 
    { 
        if(Auth::check()){ // jika sudah login, maka redirect ke halaman home 
            return redirect('/'); 
        } 
        return view('auth.login'); 
    } 

    public function register()
    {
        $levels = LevelModel::all(); // ambil level dari database
        return view('auth.register', compact('levels'));
    }

    
    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3|max:50',
            'user_kode' => 'required|min:4|max:20|unique:m_user,user_kode',
            'password' => 'required|min:6|max:20|confirmed',
            'id_level' => 'required|exists:m_level,id_level',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }
    
        try {
            $user = UserModel::create([
                'nama' => $request->nama,
                'user_kode' => $request->user_kode,
                'password' => Hash::make($request->password),
                'id_level' => $request->id_level // FIXED: harus sesuai dengan nama kolom di DB dan fillable
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Registrasi Berhasil',
                'redirect' => url('login')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    public function postlogin(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $credentials = $request->only('user_kode', 'password'); 
 
            if (Auth::attempt($credentials)) { 
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Login Berhasil', 
                    'redirect' => url('/') 
                ]); 
            } 
             
            return response()->json([ 
                'status' => false, 
                'message' => 'Login Gagal',
                'msgField' => ['user_kode' => ['Username atau Password salah']] 
            ]); 
        } 
 
        return redirect('login'); 
    } 
 
    public function logout(Request $request) 
    { 
        Auth::logout(); 
 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken();     
        return redirect('login'); 
    } 
}
