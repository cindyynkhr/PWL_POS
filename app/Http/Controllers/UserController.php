<?php

namespace App\Http\Controllers;

use App\Models\UserModel; // Import model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserController extends Controller
{
    public function index(){
        $user =UserModel::with('level')->get();
        // dd($user);
        return view('user', ['data' => $user]);
    }
    public function hapus($id){
        $user = UserModel::find($id);
        $user->delete();
        return redirect('/user');
    }
    public function ubah( $id){
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request){
        $user = UserModel::find($id);

        $user->user_kode = $request->user_kode;
        $user->nama = $request->nama;
        $user->password = Hash::make('$request->password');
        $user->level_id = $request->level_id;

        $user->save();

        return redirect('/user');
    }
    public function tambah_simpan(Request $request){
        //2.6 nomer 9
        UserModel::create([
            'user_kode' => $request -> user_kode,
            'nama' => $request -> nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id
        ]);
        return redirect('/user');
    }

    // public function index(){
    //     //2.6 dan 2.7
    //     $user = UserModel::all();
    //     return view('user', ['data' => $user]);
    // }

        public function tambah(){
            return view('user_tambah');
        }
        
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
    
}
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
