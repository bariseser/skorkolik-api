<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;

class DateHelper
{
    public function getMinutesDiff($matchStartDate)
    {
        $now = Carbon::now()->timezone('europe/istanbul');
        $matchStartDate->diffInMinutes($now);
    }
}
