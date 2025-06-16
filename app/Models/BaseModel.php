<?php
namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function serializeDate(DateTimeInterface $date): string
    {
        return $date->timezone("Europe/Istanbul")->format('Y-m-d H:i:s');
    }
}
