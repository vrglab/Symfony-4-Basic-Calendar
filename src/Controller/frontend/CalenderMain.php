<?php

namespace App\Controller\frontend;

use App\Controller\frontend\BasePage;
use App\Entity\Day;
use App\Entity\Month;
use App\Entity\Year;
use App\Service\DayService;
use App\Service\MonthService;
use Symfony\Component\HttpFoundation\Response;
use App\Service\YearService;
use Symfony\Component\Routing\Annotation\Route;

class CalenderMain extends BasePage
{

    private const MOVING_MONTH = 'movingMonth',
        MOVING_YEAR = 'movingYear',
        STATIC_MONTH = 'month',
        STATIC_YEAR = 'year',
        STATIC_DAY = 'day',
        WATCHED_DAYS = 'days',
        WATCHED_CURRENT_DAY = 'watchedCurrentDay',
        WATCHED_CURRENT_MONTH = 'watchedCurrentMonth',
        WATCHED_CURRENT_YEAR = 'watchedCurrentYear',
        STATIC_CURRENT_DAY = 'staticCurrentDay',
        STATIC_CURRENT_MONTH = 'staticCurrentMonth',
        STATIC_CURRENT_YEAR = 'staticCurrentYear',
        FIRST_WEEKDAY_OFFSET = 'firstWeekdayOffset';


    /* SERVICES AND REPOSITORIES */

    /**
     * @var YearService
     */
    private $yearService;

    /**
     * @var MonthService
     */
    private $monthService;

    /**
     * @var DayService
     */
    private $dayService;


    public function __construct(YearService $yearService, DayService $dayService, MonthService $monthService) {
        $this->yearService = $yearService;
        $this->dayService = $dayService;
        $this->monthService = $monthService;
    }


    /**
     * @var Year
     */
    private $currentYear ;

    /**
     * @var Month
     */
    private $currentMonth;

    /**
     * @var Day
     */
    private $currentDay;


    private $movingNext;
    private $movingLast;

    protected function template(): string
    {
        return "calender.html.twig";
    }

    protected function injectionMap(): array
    {
        $mapping = [
            CalenderMain::STATIC_YEAR => $this->currentYear,
            CalenderMain::STATIC_MONTH => $this->currentMonth,
            CalenderMain::STATIC_DAY => $this->currentDay,
            CalenderMain::WATCHED_DAYS => $this->currentMonth->getDays(),
            CalenderMain::MOVING_MONTH => $this->currentMonth,
            CalenderMain::MOVING_YEAR => $this->currentYear,
            CalenderMain::FIRST_WEEKDAY_OFFSET => $this->getFirstWeekdayOffset($this->currentMonth)
        ];

        if (!isset($_COOKIE[CalenderMain::STATIC_CURRENT_YEAR]) || !isset($_COOKIE[CalenderMain::STATIC_CURRENT_MONTH])) {
            if (!$this->currentYear || !$this->currentMonth) {
                $this->setCookiesAndFindData();
            }
            return $mapping;
        }

        $year = $this->getDoctrine()->getRepository(Year::class)->find(intval($_COOKIE[CalenderMain::STATIC_CURRENT_YEAR]));
        $month = $this->getDoctrine()->getRepository(Month::class)->find(intval($_COOKIE[CalenderMain::STATIC_CURRENT_MONTH]));

        $mapping[CalenderMain::STATIC_YEAR] = $year;
        $mapping[CalenderMain::STATIC_MONTH] = $month;
        $mapping[CalenderMain::STATIC_DAY] = $this->currentDay;
        $mapping[CalenderMain::FIRST_WEEKDAY_OFFSET] = $this->getFirstWeekdayOffset(
        ($this->movingLast || $this->movingNext)
            ? $this->currentMonth
            : $month);


        $mapping[CalenderMain::WATCHED_DAYS] = (($this->movingLast || $this->movingNext)? $this->currentMonth->getDays() : $month->getDays());
        $mapping[CalenderMain::MOVING_MONTH] = (($this->movingLast || $this->movingNext)? $this->currentMonth : $month);
        $mapping[CalenderMain::MOVING_YEAR] = (($this->movingLast || $this->movingNext)? $this->currentYear : $year);

        return $mapping;
    }

    private function getOrRegisterYear(int $yearNum): Year {
        $retrievedYearResult = $this->yearService->getOrMake($yearNum, $this->getDoctrine()->getRepository(Year::class));

        if($retrievedYearResult->isNew) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($retrievedYearResult->year);
            $entityManager->flush();
        }

        return $retrievedYearResult->year;
    }


    private function moveMonthAndYearToNext()
    {
        $shouldChangeYear = false;
        $activeMonth = $this->currentMonth->getMonth();

        $newMonth = $activeMonth + 1;
        if($newMonth > 12) {
            $shouldChangeYear = true;
            $newMonth = 1;
        }

        if($shouldChangeYear) {
            $this->currentYear = $this->getOrRegisterYear($this->currentYear->getYear() + 1);
            $shouldChangeYear = false;
        }

        $this->currentMonth = $this->getDoctrine()->getRepository(Month::class)->findOneBy(['year' => $this->currentYear->getId(), 'month' => $newMonth]);
        setcookie(CalenderMain::WATCHED_CURRENT_YEAR, $this->currentYear->getId());
        setcookie(CalenderMain::WATCHED_CURRENT_MONTH, $this->currentMonth->getId());
    }

    private function moveMonthAndYearToLast()
    {
        $shouldChangeYear = false;
        $activeMonth = $this->currentMonth->getMonth();

        $newMonth = $activeMonth - 1;
        if($newMonth < 1) {
            $shouldChangeYear = true;
            $newMonth = 12;
        }

        if($shouldChangeYear) {
            $this->currentYear = $this->getOrRegisterYear($this->currentYear->getYear() - 1);
            $shouldChangeYear = false;
        }

        $this->currentMonth = $this->getDoctrine()->getRepository(Month::class)->findOneBy(['year' => $this->currentYear->getId(), 'month' => $newMonth]);
        setcookie(CalenderMain::WATCHED_CURRENT_YEAR, $this->currentYear->getId());
        setcookie(CalenderMain::WATCHED_CURRENT_MONTH, $this->currentMonth->getId());
    }

    private function setCookiesAndFindData() {
        $yearString = date("Y");
        $monthString = date("m");
        $dayString = date("d");

        $retrievedYearResult = $this->getOrRegisterYear(intval($yearString));

        $retrievedMonth = $this->getDoctrine()->getRepository(Month::class)->findOneBy(['year' => $retrievedYearResult->getId(), 'month' => intval($monthString)]);
        $retrievedDay = $this->getDoctrine()->getRepository(Day::class)->findOneBy(['month' => $retrievedMonth->getId(), 'date' => intval($dayString)]);


        $this->currentYear = $retrievedYearResult;
        $this->currentMonth = $retrievedMonth;
        $this->currentDay = $retrievedDay;

        setcookie(CalenderMain::WATCHED_CURRENT_YEAR, $retrievedYearResult->getId());
        setcookie(CalenderMain::WATCHED_CURRENT_MONTH, $retrievedMonth->getId());
        setcookie(CalenderMain::WATCHED_CURRENT_DAY, $retrievedDay->getId());

        setcookie(CalenderMain::STATIC_CURRENT_YEAR, $retrievedYearResult->getId());
        setcookie(CalenderMain::STATIC_CURRENT_MONTH, $retrievedMonth->getId());
        setcookie(CalenderMain::STATIC_CURRENT_DAY, $retrievedDay->getId());
    }

    private function getFirstWeekdayOffset(Month $month): int
    {
        $yearNumber  = $month->getYear()->getYear();
        $monthNumber = $month->getMonth();

        $date = new \DateTimeImmutable(sprintf('%04d-%02d-01', $yearNumber, $monthNumber));

        $weekday = (int) $date->format('N');

        return $weekday - 1;
    }


    protected function preRender(): void
    {
        if($this->movingNext || $this->movingLast)
        {
            $this->currentYear = $this->getDoctrine()->getRepository(Year::class)->find(intval($_COOKIE[CalenderMain::WATCHED_CURRENT_YEAR]));
            $this->currentMonth = $this->getDoctrine()->getRepository(Month::class)->find(intval($_COOKIE[CalenderMain::WATCHED_CURRENT_MONTH]));
            $this->currentDay = $this->getDoctrine()->getRepository(Day::class)->find(intval($_COOKIE[CalenderMain::WATCHED_CURRENT_DAY]));

            return;
        }

        $this->setCookiesAndFindData();
    }

    protected function asRender(): void
    {
       if($this->movingNext) {
           $this->moveMonthAndYearToNext();
       }

       if($this->movingLast) {
           $this->moveMonthAndYearToLast();
       }
    }


    /**
     * @Route("/", name="home")
     */
    public function index(): Response {
        $this->movingLast = false;
        $this->movingNext = false;
        return $this->renderPage();
    }

    /**
     * @Route("/next", name="calendar_next")
     */
    public function nextMonth(): Response {
        $this->movingLast = false;
        $this->movingNext = true;
        return $this->renderPage();
    }

    /**
     * @Route("/prev", name="calendar_prev")
     */
    public function prevMonth(): Response {
        $this->movingLast = true;
        $this->movingNext = false;
        return $this->renderPage();
    }
}