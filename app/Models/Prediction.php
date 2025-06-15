<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'prediction',
        'teams',
        'comparison',
        'winner_id',
        'win_or_draw',
        'under_over',
        'has_under_over',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
        'match_id',
    ];

    protected $casts = [
        'prediction' => 'array',
        'teams' => 'array',
        'comparison' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'has_under_over' => 'boolean',
        'win_or_draw' => 'boolean',
    ];

    public function getUnderOverAttribute()
    {
        if (!empty($this->prediction['under_over'])) {
            if (str_starts_with($this->prediction['under_over'], '-')) {
                return ltrim($this->prediction['under_over'], '-') . ' ALT';
            }

            if (str_starts_with($this->prediction['under_over'], '+')) {
                return ltrim($this->prediction['under_over'], '+') . ' ÜST';
            }
            return $this->prediction['under_over'];
        }
        return $this->prediction['under_over'];
    }

    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }
}
