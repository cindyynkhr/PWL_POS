<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;  
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Iluminate\Database\QueryException;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Eloquent\Model;

class UserController extends Controller
{
    //Menampilkan data awal user
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; //set menu yang sedang aktif

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    //Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request){
        $users = UserModel::select('id_user', 'user_kode', 'nama', 'id_level')->with('level');

        return DataTables::of($users) 
        // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
        ->addIndexColumn()  
        ->addColumn('aksi', function ($user) {  // menambahkan kolom aksi 
            $btn  = '<a href="'.url('/user/' . $user->id_user).'" class="btn btn-info btn-sm">Detail</a> '; 
            $btn .= '<a href="'.url('/user/' . $user->id_user . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
            $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/' . $user->id_user).'">'
                . csrf_field() . method_field('DELETE') .  
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';      
            return $btn; 
        }) 
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
        ->make(true); 
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
            //Ussername harus diisi berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username 
            'user_kode' => 'required|string|min:3|unique:m_user, user_kode',
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
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
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
        }catch(\Iluminate\Database\QueryException $e){
            
            //Jika terjadi eror ketika menghapus data, redirect kembali ke halaman dengan membawa pesan eror
            return redirect('/user')->with('error', 'Data user gagal di hapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}

// public function index(){
//     $user =UserModel::with('level')->get();
//     // dd($user);
//     return view('user', ['data' => $user]);
// }
// public function hapus($id){
//     $user = UserModel::find($id);
//     $user->delete();
//     return redirect('/user');
// }
// public function ubah( $id){
//     $user = UserModel::find($id);
//     return view('user_ubah', ['data' => $user]);
// }

// public function ubah_simpan($id, Request $request){
//     $user = UserModel::find($id);

//     $user->user_kode = $request->user_kode;
//     $user->nama = $request->nama;
//     $user->password = Hash::make('$request->password');
//     $user->level_id = $request->level_id;

//     $user->save();

//     return redirect('/user');
// }
// public function tambah_simpan(Request $request){
//     //2.6 nomer 9
//     UserModel::create([
//         'user_kode' => $request -> user_kode,
//         'nama' => $request -> nama,
//         'password' => Hash::make($request->password),
//         'level_id' => $request->level_id
//     ]);
//     return redirect('/user');
// }

// public function index(){
//     //2.6 dan 2.7
//     $user = UserModel::all();
//     return view('user', ['data' => $user]);
// }

    // public function tambah(){
    //     return view('user_tambah');
    // }
    

    //2.5 soal 3
    // $user = UserModel::create([
    //     'user_kode' => 'manager 11',
    //     'nama' => 'Manager 11',
    //     'password' => Hash::make('12345'),
    //     'level_id' => 2
    // ]);

    // $user->user_kode = 'manager 12';

    // $user -> save();

    // $user->wasChanged();
    // $user->wasChanged('user_kode');
    // $user->wasChanged(['user_kode', 'level_id']);
    // $user->wasChanged('nama');
    // dd($user->wasChanged(['user_kode', 'nama']));
    
    //2.5 soal 1
    // $user = UserModel::create([
    //     'user_kode' => 'manager 55',
    //     'nama' => 'Manager 55',
    //     'password' => Hash::make('12345'),
    //     'level_id' => 2   
    // ]);

    // $user -> user_kode = 'manager 56';

    // $user -> isDirty();
    // $user->isDirty('user_kode');
    // $user -> isDirty('nama');
    // $user -> isDirty(['nama', 'user_kode']);

    // $user->isClean();
    // $user->isClean('user_kode');
    // $user->isClean('nama');
    // $user->isClean(['user_kode', 'nama']);

    // $user -> save();

    // $user->isDirty();
    // $user->isClean();
    // dd($user->isDirty());


    //2.4 soal 7
    // $user = UserModel::firstOrNew(
    //     [
    //         'user_kode' => 'manager33',
    //         'nama' => 'Manager tiga tiga',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 2
    //     ],
    // );
    // $user->save();

    // return view('user', ['data' => $user]);


    //2.4 soal 6
    // $user = UserModel::firstOrNew(
    //     ['user_kode'=>'manager',
    //     'nama' => 'Manager',
    //     ],
    // );
    // return view('user', ['data' => $user]);
// $user = UserModel::where('level_id', 2)->count();
// // dd($user);
// return view('user', ['data' => $user]);

// $data = [
//     'level_id' => 2,
//     'user_kode' => 'manager_tiga',
//     'nama' => 'Manager 3',
//     'password' => Hash::make('12345')
// ];
// UserModel::create($data);

// $user = UserModel::all();
// return view('user_kode', ['data' => $user]);

// $data = [
//     'user_kode' => 'customer-1',
//     'nama' => 'Pelanggan',
//     'password' => Hash::make('12345'),
//     'level_id' => 4
// ];
// UserModel::insert($data);

// $data = [
//     'nama' => 'Pelanggan Pertama',
// ];
// UserModel::where('username', 'customer-1')->update($data);

// $user = UserModel::all();
// return view('user', ['data' => $user]);
