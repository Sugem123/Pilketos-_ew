<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    public $timestamps = false;

    protected $table = 'votes';

    protected $fillable = ['id_calon', 'id_nisn', 'created_at'];

    public function calon(): BelongsTo
    {
        return $this->belongsTo(CalonKetua::class, 'id_calon');
    }

    public function hakSuara(): BelongsTo
    {
        return $this->belongsTo(HakSuara::class, 'id_nisn');
    }
}
