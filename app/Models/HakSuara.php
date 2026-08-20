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
        return $this->votes()->exists() || $this->token_used;
    }
}


