<?php

namespace App\Controller\Profile;

use App\Entity\Competition;
use App\Entity\Distance;
use App\Form\DistanceType;
use App\Repository\CompetitionRepository;
use App\Repository\DistanceRepository;
use App\Service\AttachmentService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DistanceController
 * @package App\Controller\Profile
 *
 * @Route("/competitions/{competitionId}",)
 * @IsGranted("ROLE_USER")
 */
class DistanceController extends AbstractController
{
    /**
     * @var CompetitionRepository
     */
    private $competitionRepository;

    /**
     * @var DistanceRepository
     */
    private $distanceRepository;

    /**
     * @var AttachmentService
     */
    private $attachmentService;

    /**
     * DistanceController constructor.
     * @param CompetitionRepository $competitionRepository
     * @param DistanceRepository $distanceRepository
     * @param AttachmentService $attachmentService
     */
    public function __construct(
        CompetitionRepository $competitionRepository,
        DistanceRepository $distanceRepository,
        AttachmentService $attachmentService
    ) {
        $this->competitionRepository = $competitionRepository;
        $this->distanceRepository = $distanceRepository;
        $this->attachmentService = $attachmentService;
    }

    /**
     * @Route("/distances/new", name="profile_distances_new")
     *
     * @param int $competitionId
     * @param Request $request
     * @return Response
     */
    public function newAction(int $competitionId, Request $request): Response
    {
        /** @var Competition $competition */
        $competition = $this->competitionRepository->find($competitionId);

        if (!$competition instanceof Competition || $competition->getAuthor() !== $this->getUser()) {
            throw new NotFoundHttpException();
        }

        /** @var Distance $distance */
        $distance = new Distance();

        $form = $this->createForm(DistanceType::class, $distance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $distance->setCompetition($competition);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($distance);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('profile_competitions_list'));
        }

        return $this->render('profile/distance/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/distances/edit/{id}", name="profile_distances_edit")
     *
     * @param Request $request
     * @param $competitionId
     * @param $id
     * @return RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function editAction(Request $request, int $competitionId, int $id): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Competition $competition */
        $competition = $this->competitionRepository->find($competitionId);

        if (!$competition instanceof Competition || $competition->getAuthor() !== $this->getUser()) {
            throw new NotFoundHttpException();
        }

        $distance = $this->distanceRepository->find($id);

        $form = $this->createForm(DistanceType::class, $distance);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competition = $form->getData();

            $this->attachmentService->manageAddedAttachments($competition);
            $this->attachmentService->manageRemovedAttachments($competition);

            $entityManager->persist($competition);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('profile_competitions_list'));
        }

        return $this->render('profile/distance/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
