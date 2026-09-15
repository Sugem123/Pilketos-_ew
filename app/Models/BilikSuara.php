<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BilikSuara extends Model
{
    protected $table = 'bilik_suara';

    protected $fillable = [
        'nama_bilik',
        'pairing_code',
        'is_active',
        'session_token',
        'ip_address',
        'user_agent',
        'paired_at',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'paired_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    public static function generatePairingCode(): string
    {
        do {
            $code = 'BLK-' . strtoupper(Str::random(5));
        } while (static::where('pairing_code', $code)->exists());

        return $code;
    }

    public function isPaired(): bool
    {
        return !empty($this->session_token) && $this->is_active;
    }
}
