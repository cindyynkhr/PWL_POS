<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable; //implementasi class autenticable

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'id_user';
    protected $fillable = ['user_kode', 'password', 'nama', 'id_level', 'created_at', 'updated_at'];

    protected $hidden = ['password']; // jangan di tampilkan saat select
    protected $casts = ['password' => 'hashed']; //caasting password agar otomatis di hash

    //Relasi ke tabel level
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'id_level', 'id_level');
    }

    // Mendapatkan nama role 
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    // cek apakah user memiliki role tertentu
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }

    // Mendapatkan Kode Role 
    public function getRole()
    {
        return $this->level->level_kode;
    }
}




// class UserModel extends Model
// {
//     use HasFactory;

//     protected $table = 'm_user'; // mendefinisikan nama tabel yang digunakan oleh model ini
//     protected $primaryKey = 'id_user'; // mendefinisikan primary key tabel ini

//     /**
//      * The attributes that are mass assignable.
//      * 
//      * @var array
//      */
//     protected $fillable = ['level_id','user_kode','nama','password'];

   
// }


// class LevelModel extends Model
// {
//     use HasFactory;

//     protected $table = 'm_level'; // Pastikan ini sesuai dengan nama tabel di database
//     protected $primaryKey = 'id_level'; // Primary key tabel

//     protected $fillable = ['level_nama', 'level_kode'];
//     public function user(): BelongsTo
//     {
//         return $this->belongsTo(UserModel::class, 'id_level', 'id_level');
//     }
// }

// class KategoriModel extends Model
// {
//     /**
//      * Get the barang associated with the KategoriModel.
//      *
//      * @return \Illuminate\Database\Eloquent\Relations\HasMany
//      */

//     public function barang(): HasMany
//     {
//         return $this->hasMany(BarangModel::class, 'barang_id', 'barang_id');
//     }
// }

// // One to Many (Inverse) / Belongs To
// class BarangModel extends Model
// {
//     public function kategori(): BelongsTo
//     {
//         return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
//     }
// }
