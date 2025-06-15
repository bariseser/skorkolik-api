<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'name',
        'code',
        'slug',
        'logo',
        'founded',
        'national',
        'detail',
        'stats',
    ];

    protected $casts = [
        'founded' => 'integer',
        'national' => 'boolean',
        'detail' => 'boolean',
        'stats' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function homeMatches()
    {
        return $this->hasMany(MatchModel::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(MatchModel::class, 'away_team_id');
    }

    public function events()
    {
        return $this->hasMany(MatchEvent::class);
    }

    public function lineups()
    {
        return $this->hasMany(MatchLineup::class);
    }

    public function playerStatistics()
    {
        return $this->hasMany(MatchPlayerStatistic::class);
    }

    public function teamStatistics()
    {
        return $this->hasMany(MatchTeamStatistic::class);
    }

    public function standings()
    {
        return $this->hasMany(Standing::class);
    }

    public function stats()
    {
        return $this->hasMany(TeamStat::class);
    }

    public function getLogoAttribute()
    {
        $filename = $this->attributes['logo'];

        if (!$filename) {
            return null;
        }
        return Config::get('app.url') . '/images/teams/' . $filename;
    }
}