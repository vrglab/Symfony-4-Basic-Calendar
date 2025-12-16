<?php

namespace App\Controller\backend;

use App\Entity\Year;
use App\Service\YearService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YearController extends AbstractController
{
    private $yearService;

    public function __construct(YearService $yearService) {
        $this->yearService = $yearService;
    }

    /**
     * @Route("/year/cog/{yearNum}")
     */
    public function createOrGetYear(int $yearNum): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $year = $this->yearService->getOrMake($yearNum, $entityManager->getRepository(Year::class))->year;

        $entityManager->persist($year);
        $entityManager->flush();

        return new Response('Made or found year with id '. $year->getId() . ' for year '. $yearNum);
    }
}