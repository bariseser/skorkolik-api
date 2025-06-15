<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchLineup extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'team_id',
        'coach_id',
        'formation',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function players()
    {
        return $this->hasMany(MatchLineupPlayer::class, 'lineup_id');
    }
}