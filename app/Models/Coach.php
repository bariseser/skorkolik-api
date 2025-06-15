<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'name',
        'photo',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function matchLineups()
    {
        return $this->hasMany(MatchLineup::class);
    }
}
