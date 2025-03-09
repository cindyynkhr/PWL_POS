<?php

namespace App\Http\Controllers;

use App\Models\UserModel; // Import model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserController extends Controller
{
    public function index(){
        $user = UserModel::where('user_kode','manager9') ->firstOrFail();
        return view('user', ['data' => $user]);
    }
}

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
