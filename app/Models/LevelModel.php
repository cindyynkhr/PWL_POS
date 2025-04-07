<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Pastikan ini sesuai dengan nama tabel di database
    protected $primaryKey = 'id_level'; // Primary key tabel

    protected $fillable = ['level_kode', 'level_nama'];
    public function user(): HasMany
    {
        return $this->HasMany(UserModel::class);
    }
}
