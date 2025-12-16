<?php

namespace App\Service;

use App\Entity\Day;

class DayService
{
    public function makeDay($date, $month, $weekendDay): Day
    {
        $day = new Day();
        $day->setDate($date);
        $day->setMonth($month);
        $day->setWeekendDay($weekendDay);
        return $day;
    }
}