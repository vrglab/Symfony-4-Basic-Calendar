<?php

namespace App\Service;

use App\Controller\backend\MonthController;
use App\Entity\Month;
use App\Entity\Year;

class MonthService
{
    private $dayService;

    public function __construct(DayService $dayService) {
        $this->dayService = $dayService;
    }


    public function makeMonth(int $monthNum, Year $year): Month
    {
        $month = new Month();
        $month->setMonth($monthNum);
        $month->setYear($year);
        $daysInMonth = MonthController::$MONTH_DAY_MAPPING[$monthNum];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = new \DateTime(sprintf('%d-%02d-%02d', $year->getYear(), $monthNum, $i));
            $isWeekend = (int)$date->format('N') >= 6;

            $month->addDay($this->dayService->makeDay($i, $month, $isWeekend));
        }

        return $month;
    }
}