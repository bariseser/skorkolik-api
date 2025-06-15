<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'team_id',
        'player_id',
        'assist_id',
        'elapsed',
        'extra',
        'event',
        'detail',
    ];

    protected $casts = [
        'elapsed' => 'integer',
        'extra' => 'integer',
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

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function assist()
    {
        return $this->belongsTo(Player::class, 'assist_id');
    }
}