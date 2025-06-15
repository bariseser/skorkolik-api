<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'season_id',
        'team_id',
        'rank',
        'point',
        'goals_diff',
        'form',
        'played',
        'win',
        'draw',
        'lose',
        'goals_for',
        'goals_against',
    ];

    protected $casts = [
        'rank' => 'integer',
        'point' => 'integer',
        'goals_diff' => 'integer',
        'played' => 'integer',
        'win' => 'integer',
        'draw' => 'integer',
        'lose' => 'integer',
        'goals_for' => 'integer',
        'goals_against' => 'integer',
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

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}