<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'id_user';

    protected $fillable = ['id_level', 'user_kode', 'nama', 'password'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'id_level', 'id_level');
    }
}

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

class KategoriModel extends Model
{
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Get the barang associated with the KategoriModel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

/******  87d250b5-53b3-47be-8f30-c9b0b54f032b  *******/
    public function barang(): HasMany
    {
        return $this->hasMany(BarangModel::class, 'barang_id', 'barang_id');
    }
}

// One to Many (Inverse) / Belongs To
class BarangModel extends Model
{
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}
