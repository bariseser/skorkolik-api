<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchLineupPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'lineup_id',
        'player_id',
        'number',
        'position',
        'grid',
        'is_starter',
    ];

    protected $casts = [
        'number' => 'integer',
        'is_starter' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function lineup()
    {
        return $this->belongsTo(MatchLineup::class, 'lineup_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}