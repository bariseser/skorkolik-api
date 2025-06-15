<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'season_id',
        'team_id',
        'stats',
    ];

    protected $casts = [
        'stats' => 'array',
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