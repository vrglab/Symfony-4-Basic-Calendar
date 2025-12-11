<?php
namespace App\Controller\backend;


use App\Entity\Day;
use App\Service\DayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DayController extends AbstractController
{

    private $dayService;

    public function __construct(DayService $dayService)
    {
        $this->dayService = $dayService;
    }


    /**
     * @Route("/day", name="create_day")
     */
    public function createDay(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $day = $this->dayService->makeDay(1, 11, false);


        $entityManager->persist($day);
        $entityManager->flush();

        return new Response('Saved new product with id '. $day->getId());
    }

    /**
     * @Route("/day/{id}", name="day_get")
     */
    public function getDay(int $id): Response
    {
       $day = $this->getDoctrine()->getRepository(Day::class)->find($id);

        if (!$day) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Product with id '. $day->getId());
    }
}