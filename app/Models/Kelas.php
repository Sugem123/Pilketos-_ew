<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    public $timestamps = false;

    protected $table = 'kelas';

    protected $fillable = ['name'];

    public function calonKetua(): HasMany
    {
        return $this->hasMany(CalonKetua::class, 'id_kelas');
    }

    public function hakSuara(): HasMany
    {
        return $this->hasMany(HakSuara::class, 'id_kelas');
    }
}

