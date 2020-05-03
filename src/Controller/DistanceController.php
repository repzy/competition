<?php

namespace App\Controller;

use App\Repository\DistanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DistanceController extends AbstractController
{
    /**
     * @var DistanceRepository
     */
    private $distanceRepository;

    /**
     * DistanceController constructor.
     * @param DistanceRepository $distanceRepository
     */
    public function __construct(DistanceRepository $distanceRepository)
    {
        $this->distanceRepository = $distanceRepository;
    }

    /**
     * @Route("/distances/{id}", name="distances_show")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function showAction(Request $request, int $id): Response
    {
        $distance = $this->distanceRepository->find($id);

        return $this->render('distance/show.html.twig', [
            'distance' => $distance,
        ]);
    }
}