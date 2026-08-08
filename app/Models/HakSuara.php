<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HakSuara extends Model
{
    public $timestamps = false;

    protected $table = 'hak_suara';

    protected $fillable = ['nisn'];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'id_nisn');
    }

    public function hasVoted(): bool
    {
        return $this->votes()->exists();
    }
}
