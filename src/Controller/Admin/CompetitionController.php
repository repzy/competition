<?php

namespace App\Controller\Admin;

use App\Entity\Attachment;
use App\Entity\Competition;
use App\Form\CompetitionType;
use App\Form\SearchType;
use App\Repository\CompetitionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\PersistentCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/competitions")
 *
 */
//@IsGranted("ROLE_ADMIN")
class CompetitionController extends AbstractController
{
    /**
     * @var CompetitionRepository
     */
    private $competitionRepository;

    public function __construct(CompetitionRepository $competitionRepository)
    {
        $this->competitionRepository = $competitionRepository;
    }

    /**
     * @Route("/", name="admin_competitions_list")
     * @Route("/page{page}/", name="admin_competitions_list_paging", requirements={"page": "\d+"})
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

        return $this->render('admin/competition/list.html.twig', [
            'competitions' => $competitions
        ]);
    }

    /**
     * @Route("/new", name="admin_competitions_new")
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($competition);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('admin_competitions_list'));
        }

        return $this->render('admin/competition/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_competitions_edit")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Competition $competition */
        $competition = $this->competitionRepository->find($id);

        $form = $this->createForm(CompetitionType::class, $competition);

        /** @var PersistentCollection $oldAttachments */
        $oldAttachments = $competition->getAttachments();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competition = $form->getData();

            /** @var Attachment[] $newAttachments */
            $newAttachments = $competition->getAttachments()->getInsertDiff();
            foreach ($newAttachments as $newAttachment) {
                $newAttachment->setPath($newAttachment->getFile()->getClientOriginalName());
                $entityManager->persist($newAttachment);
            }

            $entityManager->persist($competition);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('admin_competitions_list'));
        }

        return $this->render('admin/competition/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_competitions_delete")
     */
    public function deleteAction()
    {

    }
}
