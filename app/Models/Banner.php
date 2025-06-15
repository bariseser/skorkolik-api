<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';

    protected $fillable = [
        'text',
        'banner',
        'active',
        'link'
    ];

    protected $hidden = [
        'active',
        'created_at',
        'updated_at'
    ];
}
