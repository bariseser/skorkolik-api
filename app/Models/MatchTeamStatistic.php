<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchTeamStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'team_id',
        'stats',
    ];

    protected $casts = [
        'stats' => 'array',
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
}