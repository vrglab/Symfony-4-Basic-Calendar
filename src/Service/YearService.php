<?php

namespace App\Service;

use App\Entity\Month;
use App\Entity\Year;
use App\Utils\GetOrMakeResult;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class YearService
{

    private $monthService;

    public function __construct(MonthService $monthService) {
        $this->monthService = $monthService;
    }

    public function makeYear($yearNum): Year {
        $year = new Year();
        $year->setYear($yearNum);

        for ($i = 1; $i <= 12; $i++) {
            $year->addMonth($this->monthService->makeMonth($i, $year));
        }

        return $year;
    }

    public function getOrMake($yearNum, ObjectRepository $repository): GetOrMakeResult {
        $isNew = false;
        $year = $repository->findOneBy(["year" => $yearNum]);

        if($year === null) {
            $year = $this->makeYear($yearNum);
            $isNew = true;
        }

        return new GetOrMakeResult($year, $isNew);
    }
}