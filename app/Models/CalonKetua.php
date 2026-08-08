<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalonKetua extends Model
{
    protected $table = 'calon_ketua';

    public $timestamps = false;

    protected $fillable = ['nama', 'nomor', 'visi', 'misi', 'id_kelas', 'url_foto'];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'id_calon');
    }

    public function voteCount(): int
    {
        return $this->votes()->count();
    }
}
