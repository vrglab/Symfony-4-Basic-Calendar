<?php

namespace App\Controller\backend;

use App\Entity\Month;
use App\Service\MonthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonthController extends AbstractController
{

    public static $MONTH_DAY_MAPPING =
    [
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31
    ];


    private $monthService;

    public function __construct(MonthService $monthService) {
        $this->monthService = $monthService;
    }


    /**
     * @Route("/month/c/{monthNum}", name="create_month")
     */
    public function createMonth(int $monthNum): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $month = $this->monthService->makeMonth($monthNum);
        $entityManager->persist($month);
        $entityManager->flush();

        return new Response('Saved new month with id '. $month->getId() . ' for month '. $monthNum . ' with days '. count($month->getDays()));
    }
}