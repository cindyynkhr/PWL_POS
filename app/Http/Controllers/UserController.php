<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;  
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; //set menu yang sedang aktif

        $level = LevelModel::all(); //ambil data level untuk filter level 

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level,'activeMenu' => $activeMenu]);
    }
    
    //Store_ajax
    public function store_ajax(Request $request) {
        //cek apakah request berupa ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'id_level'  => 'required|integer',
                 'user_kode' => 'required|string|min:3|unique:m_user,user_kode',
                 'nama'      => 'required|string|max:100',
                 'password'  => 'required|min:6'
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // response status, false: error/gagal, true: berhasil
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors(), //pesan eror validasi
                 ]);
             }
 
             UserModel::create($request->all()); //simpan data user baru ke tabel m_user
        
             return response()->json([
                 'status' => true,
                 'message' => 'Data user berhasil disimpan'
             ]);
         }
 
         return redirect('/user');
     }

    //Create_ajax
    public function create_ajax(){
        $level = LevelModel::select('id_level', 'level_nama')->get();

        return view('user.create_ajax')->with('level', $level);
    }

    //Menampilkan data awal user
   

    public function list(Request $request){
        $user = UserModel::select('id_user', 'user_kode', 'nama', 'id_level')->with('level');

        if ($request->id_level) {
            $user->where('id_level', $request->id_level);
        }

        return DataTables::of($user)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\''.url('/user/' . $user->id_user . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->id_user . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->id_user . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                
                return $btn;
            })        
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true); 
        }

    //Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id){
        $user = UserModel::find($id);
        $level = LevelModel::select('id_level', 'level_nama')->get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    } 

    public function update_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'id_level' => 'required|integer',
                 'user_kode' => 'required|max:20|unique:m_user,user_kode,'.$id.',id_user',
                 'nama' => 'required|max:100',
                 'password' => 'nullable|min:6|max:20'
             ];
 
             // validasi
             $validator = Validator::make($request->all(), $rules);
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // respon json, true: berhasil, false: gagal
                     'message' => 'Validasi gagal.',
                     'msgField' => $validator->errors() // menunjukkan field mana yang error
                 ]);
             }
             $check = UserModel::find($id);
             if ($check) {
                 if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request
                     $request->request->remove('password');
                 }
                 $check->update($request->all());
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diupdate'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
         return redirect('/user');
     }
 
     public function confirm_ajax(String $id)
     {
         $user = UserModel::find($id);
         return view('user.confirm_ajax', ['user' => $user]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $user = UserModel::find($id);
             if ($user) {
                 $user->delete();
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil dihapus'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
         return redirect('/user');
     }

    //Menampilkan halaman form tambh user
    Public function create(){
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Form Tambah User'
        ];

        $level = LevelModel::all();//ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; //set menu yang sedang aktif

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]); 
    }

    //Menyikmpan data user baru
    public function store(Request $request){
        $request->validate([
            //Ussername harus diisi berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom user_kode

            'user_kode' => 'required|string|min:3|unique:m_user,user_kode',
            'nama' => 'required|string|max:100', //nama harus diidi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5', //password harus diisi, minimal 5 karakter
            'id_level' => 'required|integer' //id_level harus diisi, berupa angka
        ]);

        UserModel::create([
            'user_kode' => $request->user_kode,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), //password dienkripsi sebelum disimpan
            'id_level' => $request->id_level
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    //Menampilakm detail user
    public function show($id){
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail User'
        ];

        $activeMenu = 'user'; //set menu yang sedang aktif

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    //Menampilkan halaman form edit user
    public function edit(string $id){
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list'  => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    //Menyimpan perubahan data user
    public function update(Request $request, string $id){
        $request->validate([
            // user_kode
             //harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom user_kode
             //kecuali untuk user dengan id yang sedang diedit
            'user_kode' => 'required|string|min:3|unique:m_user,user_kode,' . $id . ',id_user',
            'nama'     => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'id_level' => 'required|integer' // level_id harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'user_kode' => $request->user_kode,
            'nama'     => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'id_level' => $request->id_level
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    //menghapus data user
    public function destroy(string $id){
        $check = UserModel::find($id);
        if (!$check){   //untuk mengecek aakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }
        try{
            UserModel::destroy($id); //Hapus data level
        
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){
            
            //Jika terjadi eror ketika menghapus data, redirect kembali ke halaman dengan membawa pesan eror
            return redirect('/user')->with('error', 'Data user gagal di hapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
