<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class HakSuara extends Model
{
    public $timestamps = false;

    protected $table = 'hak_suara';

    protected $fillable = ['nisn', 'tipe', 'id_kelas', 'token', 'token_used'];

    protected function casts(): array
    {
        return [
            'token_used' => 'boolean',
        ];
    }

    public static function generateUniqueToken(): string
    {
        do {
            $token = strtoupper(Str::random(6));
        } while (static::where('token', $token)->exists() || Token::where('token', $token)->exists());

        return $token;
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'id_nisn');
    }

    public function hasVoted(): bool
    {
        return $this->token_used
            || $this->votes()->exists();
    }

    public function hasVotedFor(string $tipe): bool
    {
        return $this->votes()->where('tipe_pemilihan', $tipe)->exists();
    }

    public function hasVotedBothElections(): bool
    {
        return $this->hasVotedFor(CalonKetua::TIPE_OSIS)
            && $this->hasVotedFor(CalonKetua::TIPE_MPK);
    }

    public function remainingElections(): array
    {
        $sisa = [];

        if (! $this->hasVotedFor(CalonKetua::TIPE_OSIS)) {
            $sisa[] = CalonKetua::TIPE_OSIS;
        }

        if (! $this->hasVotedFor(CalonKetua::TIPE_MPK)) {
            $sisa[] = CalonKetua::TIPE_MPK;
        }

        return $sisa;
    }
}


