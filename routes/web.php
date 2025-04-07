<?php

use App\Models\User;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierController;
use App\http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::pattern('id', '[0-9]+');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function(){
    Route::get('/',[WelcomeController::class,'index']);

    //user
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index']);                      //menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']);                  //menampilkan data user dalam bentuk json untuk datablase
        Route::get('/create', [UserController::class, 'create']);               //menampilkan halaman form user
        Route::post('/', [UserController::class, 'store']);                     //menyimpan data user baru
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);     //Menampilkan halaman form tambah user ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);            //menyimpan data user baru ajax
        Route::get('/{id}', [UserController::class, 'show']);                   //menampilkan detail user
        Route::get('/{id}/edit', [UserController::class, 'edit']);              //menampilkan halaman form edit user
        Route::put('/{id}', [UserController::class, 'update']);                 //menyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);    //menampilkan halaman form edit user ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); //menyimpan perubahan data user
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);   //mengonfirmasi data user
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); 
        Route::delete('/{id}', [UserController::class, 'destroy']);             //menghapus data user
    });
    
    // route untuk level
    // Route::prefix('level')->group(function () {
    //     Route::get('/', [LevelController::class, 'index']);
    //     Route::post('/list', [LevelController::class, 'list']);
    //     Route::get('/create', [LevelController::class, 'create']);
    //     Route::post('/', [LevelController::class, 'store']);                     
    //     Route::get('/create_ajax', [LevelController::class, 'create_ajax']);     
    //     Route::post('/ajax', [LevelController::class, 'store_ajax']); 
    //     Route::get('/{id}', [LevelController::class, 'show']);
    //     Route::get('/{id}/edit', [LevelController::class, 'edit']);
    //     Route::put('/{id}', [LevelController::class, 'update']);
    //     Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);    //menampilkan halaman form edit Level ajax
    //     Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); //menyimpan perubahan data Level
    //     Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);   //mengonfirmasi data Level
    //     Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); 
    //     Route::delete('/{id}', [LevelController::class, 'destroy']);
    // });

    //Route dalam grup level harus punya role ADM
    Route::middleware('authorize:ADM, MNG')->group(function () {
        Route::prefix('level')->group(function () {
            Route::get('/', [LevelController::class, 'index']);
            Route::post('/list', [LevelController::class, 'list']);
            Route::get('/create', [LevelController::class, 'create']);
            Route::post('/', [LevelController::class, 'store']);                     
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']);     
            Route::post('/ajax', [LevelController::class, 'store_ajax']); 
            Route::get('/{id}', [LevelController::class, 'show']);
            Route::get('/{id}/edit', [LevelController::class, 'edit']);
            Route::put('/{id}', [LevelController::class, 'update']);
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);    //menampilkan halaman form edit Level ajax
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); //menyimpan perubahan data Level
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);   //mengonfirmasi data Level
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); 
            Route::delete('/{id}', [LevelController::class, 'destroy']);
        });
    });
    
    // route untuk kategori
    Route::prefix('kategori')->group(function() {
        Route::get('/', [KategoriController::class, 'index']);
        Route::get('/create', [KategoriController::class, 'create']);
        Route::post('/', [KategoriController::class, 'store']);
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);     
        Route::post('/ajax', [KategoriController::class, 'store_ajax']); 
        Route::get('/{id}', [KategoriController::class, 'show']);
        Route::get('/{id}/edit', [KategoriController::class, 'edit']);
        Route::put('/{id}', [KategoriController::class, 'update']);
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);    //menampilkan halaman form edit Kategori ajax
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); //menyimpan perubahan data Kategori
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);   //mengonfirmasi data Kategori
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); 
        Route::delete('/{id}', [KategoriController::class, 'destroy']);
        Route::post('/list', [KategoriController::class, 'list']);
    });
    
    // route untuk stok
    Route::prefix('stok')->group(function () {
        Route::get('/', [StokController::class, 'index']);
        Route::post('/list', [StokController::class, 'list']);
        Route::get('/create', [StokController::class, 'create']);
        Route::post('/', [StokController::class, 'store']);
        Route::get('/{id}', [StokController::class, 'show']);
        Route::get('/{id}/edit', [StokController::class, 'edit']);
        Route::put('/{id}', [StokController::class, 'update']);
        Route::delete('/{id}', [StokController::class, 'destroy']);
    });
    
    // route untuk barang
    Route::prefix('barang')->group(function() {
        Route::get('/', [BarangController::class, 'index']);
        Route::post('/list', [BarangController::class, 'list']);
        Route::get('/create', [BarangController::class, 'create']);
        Route::post('/', [BarangController::class, 'store']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);     
        Route::post('/ajax', [BarangController::class, 'store_ajax']); 
        Route::get('/{id}', [BarangController::class, 'show']);
        Route::get('/{id}/edit', [BarangController::class, 'edit']);
        Route::put('/{id}', [BarangController::class, 'update']);
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);    //menampilkan halaman form edit Barang ajax
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); //menyimpan perubahan data Barang
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);   //mengonfirmasi data Barang
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); 
        Route::delete('/{id}', [BarangController::class, 'destroy']);
    });
    
    // route untuk supplier
    Route::group(['prefix' => 'supplier'], function () {
        Route::get('/', [SupplierController::class, 'index']);           // menampilkan halaman awal user
        Route::post('/list', [SupplierController::class, 'list']);       // menampilkan data user dalam bentuk json untuk datatables
        Route::get('/create', [SupplierController::class, 'create']);    // menampilkan halaman form tambah user
        Route::post('/', [SupplierController::class, 'store']);          // menyimpan data user baru
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);     
        Route::post('/ajax', [SupplierController::class, 'store_ajax']); 
        Route::get('/{id}', [SupplierController::class, 'show']);        // menampilkan detail user
        Route::get('/{id}/edit', [SupplierController::class, 'edit']);   // menampilkan halaman form edit user
        Route::put('/{id}', [SupplierController::class, 'update']);      // menyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);    //menampilkan halaman form edit Supplier ajax
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); //menyimpan perubahan data Supplier
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);   //mengonfirmasi data Supplier
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); 
        Route::delete('/{id}', [SupplierController::class, 'destroy']);  // menghapus data user
    });

});


// Route::get('/level',[LevelController::class,'index']);
// Route::get('/kategori',[KategoriController::class,'index']);
// Route::get('/user',[UserController::class,'index']);
// Route::get('/user/tambah',[UserController::class,'tambah']);
// Route::post('/user/tambah_simpan',[UserController::class,'tambah_simpan']);
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']); // Untuk menampilkan form
// Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']); // Untuk menyimpan perubahan
// Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

// Route::get('/', function () {
//     return view('welcome1');
// });