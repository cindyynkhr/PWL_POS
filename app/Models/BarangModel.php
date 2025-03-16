<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    protected $table = 'm_barang';
     protected $primaryKey = 'barang_id';
     protected $fillable = ['barang_id', 'id_kategori', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual'];
 
     public function kategori()
     {
         return $this->belongsTo(KategoriModel::class, 'id_kategori', 'id_kategori');
     }
 
     public function stok()
 {
     return $this->hasOne(StokModel::class, 'barang_id', 'barang_id');
 }
}
