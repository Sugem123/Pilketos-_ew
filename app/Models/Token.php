<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $timestamps = false;

    protected $fillable = ['token', 'active'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
