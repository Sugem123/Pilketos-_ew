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

    protected $fillable = [
        'tipe',
        'nama',
        'nama_wakil_1',
        'nama_wakil_2',
        'nomor',
        'visi',
        'misi',
        'id_kelas',
        'id_kelas_wakil_1',
        'id_kelas_wakil_2',
        'url_foto',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function kelasWakil1(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas_wakil_1');
    }

    public function kelasWakil2(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas_wakil_2');
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
        return $this->tipe === self::TIPE_MPK ? 'Calon Ketua MPK' : 'Calon Ketua OSIS';
    }

    public function labelPaslon(): string
    {
        return $this->tipe === self::TIPE_MPK ? 'Pasangan Calon MPK' : 'Pasangan Calon OSIS';
    }

    public function namaLengkapPaslon(): string
    {
        $parts = [$this->nama];
        if (!empty($this->nama_wakil_1)) {
            $parts[] = $this->nama_wakil_1;
        }
        if (!empty($this->nama_wakil_2)) {
            $parts[] = $this->nama_wakil_2;
        }

        return implode(' & ', $parts);
    }
}
