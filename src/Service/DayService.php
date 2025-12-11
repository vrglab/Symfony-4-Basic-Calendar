<?php

namespace App\Service;

use App\Entity\Day;
use Doctrine\Persistence\ObjectManager;

class DayService
{
    public function makeDay($date, $month, $weekendDay) {
        $day = new Day();
        $day->setDate($date);
        $day->setMonth($month);
        $day->setWeekendDay($weekendDay);
        return $day;
    }
}