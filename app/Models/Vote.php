<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    public $timestamps = false;

    protected $table = 'votes';

    protected $fillable = [
        'id_calon',
        'id_nisn',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function calon(): BelongsTo
    {
        return $this->belongsTo(CalonKetua::class, 'id_calon');
    }

    public function hakSuara(): BelongsTo
    {
        return $this->belongsTo(HakSuara::class, 'id_nisn');
    }
}

