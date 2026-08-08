<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function calonKetua(): HasMany
    {
        return $this->hasMany(CalonKetua::class, 'id_kelas');
    }
}
