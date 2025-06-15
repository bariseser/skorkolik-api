<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'name',
        'first_name',
        'last_name',
        'birth_date',
        'nationality',
        'height',
        'weight',
        'number',
        'position',
        'photo',
        'detail',
        'stats',
    ];

    protected $casts = [
        'number' => 'integer',
        'detail' => 'boolean',
        'stats' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function events()
    {
        return $this->hasMany(MatchEvent::class);
    }

    public function assists()
    {
        return $this->hasMany(MatchEvent::class, 'assist_id');
    }

    public function lineupPlayers()
    {
        return $this->hasMany(MatchLineupPlayer::class);
    }

    public function statistics()
    {
        return $this->hasMany(MatchPlayerStatistic::class);
    }

    public function getPhotoAttribute()
    {
        $filename = $this->attributes['photo'];

        if (!$filename) {
            return null;
        }
        return Config::get('app.url') . '/images/players/' . $filename;
    }
}