<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Models\User;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Monolog\Level;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome1');
});

Route::get('/level',[LevelController::class,'index']);
Route::get('/kategori',[KategoriController::class,'index']);
Route::get('/user',[UserController::class,'index']);
Route::get('/user/tambah',[UserController::class,'tambah']);
Route::post('/user/tambah_simpan',[UserController::class,'tambah_simpan']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']); // Untuk menampilkan form
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']); // Untuk menyimpan perubahan
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);
Route::get('/',[WelcomeController::class,'index']);