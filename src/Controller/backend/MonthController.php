<?php

namespace App\Controller\backend;

use App\Entity\Month;
use App\Service\DayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonthController extends AbstractController
{
    private $dayService;

    public function __construct(DayService $dayService) {
        $this->dayService = $dayService;
    }


    /**
     * @Route("/month/c/{monthNum}", name="create_month")
     */
    public function createMonth(int $monthNum): Response
    {

        $entityManager = $this->getDoctrine()->getManager();


        $month = new Month();
        $month->setMonth($monthNum);

        $daysInMonth = 30;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $month->addDay($this->dayService->makeDay($i, $monthNum, false));
        }

        $entityManager->persist($month);
        $entityManager->flush();

        return new Response('Saved new month with id '. $month->getId());
    }
}