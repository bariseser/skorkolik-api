<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'country_id',
        'name',
        'type',
        'logo',
        'slug',
        'active',
        'order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getLogoAttribute()
    {
        $filename = $this->attributes['logo'];

        if (!$filename) {
            return null;
        }
        return Config::get('app.cdn') . '/leagues/' . $filename;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function seasons()
    {
        return $this->hasMany(LeagueSeason::class);
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function standings()
    {
        return $this->hasMany(Standing::class);
    }

    public function teamStats()
    {
        return $this->hasMany(TeamStat::class);
    }
}
