<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class KategoriModel extends Model
{
    protected $table = 'm_kategori';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['kategori_kode', 'kategori_nama'];

    public function barang()
    {
        return $this->hasMany(BarangModel::class, 'id_kategori', 'id_kategori');
    }

}
