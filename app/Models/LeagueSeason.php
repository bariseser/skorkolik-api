<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueSeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'year',
        'current',
        'active',
        'standings',
        'players',
        'events',
        'lineups',
        'statistics_fixtures',
        'statistics_players',
        'start_date',
        'end_date',
        'prediction',
    ];

    protected $casts = [
        'year' => 'integer',
        'current' => 'boolean',
        'active' => 'boolean',
        'standings' => 'boolean',
        'players' => 'boolean',
        'events' => 'boolean',
        'lineups' => 'boolean',
        'statistics_fixtures' => 'boolean',
        'statistics_players' => 'boolean',
        'prediction' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class, 'season_id');
    }

    public function rounds()
    {
        return $this->hasMany(Round::class, 'season_id');
    }

    public function standings()
    {
        return $this->hasMany(Standing::class, 'season_id');
    }

    public function teamStats()
    {
        return $this->hasMany(TeamStat::class, 'season_id');
    }
}
