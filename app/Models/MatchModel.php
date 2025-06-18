<?php

namespace App\Models;

use App\Facades\DateHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatchModel extends BaseModel
{
    use HasFactory;

    protected $table = 'matches';

    protected $appends = ['match_minute', 'match_time'];

    protected $fillable = [
        'provider_id',
        'league_id',
        'season_id',
        'round_id',
        'home_team_id',
        'away_team_id',
        'status',
        'period',
        'home_goals',
        'away_goals',
        'halftime_home',
        'halftime_away',
        'fulltime_home',
        'fulltime_away',
        'extra_home',
        'extra_away',
        'penalty_home',
        'penalty_away',
        'home_winner',
        'away_winner',
        'timestamp',
        'event_imported',
        'lineup_imported',
        'team_stats_imported',
        'player_stats_imported',
        'match_date',
        'match_start_date',
        'prediction',
    ];

    protected $casts = [
        'home_goals' => 'integer',
        'away_goals' => 'integer',
        'halftime_home' => 'integer',
        'halftime_away' => 'integer',
        'fulltime_home' => 'integer',
        'fulltime_away' => 'integer',
        'extra_home' => 'integer',
        'extra_away' => 'integer',
        'penalty_home' => 'integer',
        'penalty_away' => 'integer',
        'home_winner' => 'boolean',
        'away_winner' => 'boolean',
        'timestamp' => 'integer',
        'event_imported' => 'boolean',
        'lineup_imported' => 'boolean',
        'team_stats_imported' => 'boolean',
        'player_stats_imported' => 'boolean',
        'match_start_date' => 'datetime',
        'match_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'match_minute' => 'string',
        'match_time' => 'datetime',
    ];

    protected $hidden = [
        'provider_id',
        'updated_at',
        'event_imported',
        'lineup_imported',
        'team_stats_imported',
        'player_stats_imported',
    ];

    public function getMatchMinuteAttribute(): string
    {
        $matchDate = Carbon::createFromFormat('Y-m-d H:i:s', $this->match_date);
        $now = Carbon::now();
        $diffInMinutes = (int)$matchDate->diffInMinutes($now, true);
        switch ($this->status) {
            case "playing" :
                switch ($this->period){
                    case "first_half" :
                        if ($diffInMinutes <= 45) {
                            return $diffInMinutes;
                        } else {
                            return "45+".($diffInMinutes - 45);
                        }
                    case "second_half" :
                        $diffInMinutes = $diffInMinutes - 15;
                        if ($diffInMinutes > 90) {
                            return "90+".($diffInMinutes - 90);
                        } else {
                            return $diffInMinutes;
                        }
                    case "half_time";
                        return "IY";
                    case 'extra_time':
                        return $diffInMinutes;
                    case 'extra_half_time':
                        return "UZ";
                    case "penalty":
                        return "PEN";
                    default:
                        return "0";
                }
            case "postponed" :
                return "ERT";
            case "cancelled" :
                return "IPT";
            case 'played':
                return "MS";
            default:
                return "0";
        }
    }

    public function getMatchTimeAttribute(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->match_date)->timezone("Europe/Istanbul")->format('H:i');
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function season()
    {
        return $this->belongsTo(LeagueSeason::class, 'season_id');
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function events()
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }

    public function lineups()
    {
        return $this->hasMany(MatchLineup::class, 'match_id');
    }

    public function playerStatistics()
    {
        return $this->hasMany(MatchPlayerStatistic::class, 'match_id');
    }

    public function teamStatistics()
    {
        return $this->hasMany(MatchTeamStatistic::class, 'match_id');
    }

    public function predictions()
    {
        return $this->hasOne(Prediction::class, 'match_id');
    }
}
