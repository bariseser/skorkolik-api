<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;

class DateHelper
{
    public function getMinutesDiff($matchStartDate)
    {
        $now = new DateTime(Carbon::now()->toDateTimeString());
        $startedDate = new DateTime( $matchStartDate->toDateTimeString());
        $diff = $now->diff($startedDate);
        return ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
    }
}
