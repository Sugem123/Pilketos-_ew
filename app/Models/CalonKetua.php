<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalonKetua extends Model
{
    public const TIPE_OSIS = 'osis';
    public const TIPE_MPK = 'mpk';

    protected $table = 'calon_ketua';

    public $timestamps = false;

    protected $fillable = ['tipe', 'nama', 'nomor', 'visi', 'misi', 'id_kelas', 'url_foto'];

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

    public function scopeOsis($query)
    {
        return $query->where('tipe', self::TIPE_OSIS);
    }

    public function scopeMpk($query)
    {
        return $query->where('tipe', self::TIPE_MPK);
    }

    public function labelTipe(): string
    {
        return $this->tipe === self::TIPE_MPK ? 'Ketua MPK' : 'Ketua OSIS';
    }
}
