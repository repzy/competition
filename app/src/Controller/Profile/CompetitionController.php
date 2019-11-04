<?php

namespace App\Controller\Profile;

use App\Entity\Competition;
use App\Form\CompetitionType;
use App\Form\SearchType;
use App\Repository\CompetitionRepository;
use App\Service\AttachmentService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/competitions")
 * @IsGranted("ROLE_USER")
 */
class CompetitionController extends AbstractController
{
    /**
     * @var CompetitionRepository
     */
    private $competitionRepository;

    /**
     * @var AttachmentService
     */
    private $attachmentService;

    /**
     * CompetitionController constructor.
     * @param CompetitionRepository $competitionRepository
     * @param AttachmentService $attachmentService
     */
    public function __construct(
        CompetitionRepository $competitionRepository,
        AttachmentService $attachmentService
    ) {
        $this->competitionRepository = $competitionRepository;
        $this->attachmentService = $attachmentService;
    }

    /**
     * @Route("/", name="profile_competitions_list")
     * @Route("/page{page}/", name="profile_competitions_list_paging", requirements={"page": "\d+"})
     *
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $searchForm = $this->createForm(SearchType::class);

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $data['page'] = (int) $request->get('page');
        } else {
            $data = ['page' => (int) $request->get('page')];
        }

        if ($data['page'] === 0) {
            $data['page'] = 1;
        }

        $competitions = $this->competitionRepository->search($data);

        return $this->render('profile/competition/list.html.twig', [
            'competitions' => $competitions
        ]);
    }

    /**
     * @Route("/new", name="profile_competitions_new")
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        /** @var Competition $competition */
        $competition = new Competition();

        $form = $this->createForm(CompetitionType::class, $competition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competition->setAuthor($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($competition);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('profile_competitions_list'));
        }

        return $this->render('profile/competition/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="profile_competitions_edit")
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function editAction(Request $request, $id)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Competition $competition */
        $competition = $this->competitionRepository->find($id);

        if (!$competition instanceof Competition || $competition->getAuthor() !== $this->getUser()) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(CompetitionType::class, $competition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competition = $form->getData();

            $this->attachmentService->manageAddedAttachments($competition);
            $this->attachmentService->manageRemovedAttachments($competition);

            $entityManager->persist($competition);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('profile_competitions_list'));
        }

        return $this->render('profile/competition/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="profile_competitions_delete")
     */
    public function deleteAction()
    {

    }
}
