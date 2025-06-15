<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'season_id',
        'name',
        'slug',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function season()
    {
        return $this->belongsTo(LeagueSeason::class, 'season_id');
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }
}