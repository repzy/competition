<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Competition;
use App\Form\SearchType;
use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/competitions")
 */
class CompetitionController extends AbstractController
{
    /**
     * @var CompetitionRepository
     */
    private $competitionRepository;

    /**
     * CompetitionController constructor.
     * @param CompetitionRepository $competitionRepository
     */
    public function __construct(CompetitionRepository $competitionRepository)
    {
        $this->competitionRepository = $competitionRepository;
    }

    /**
     * @Route("/", name="competitions_list")
     * @Route("/page{page}/", name="competitions_list_paging", requirements={"page": "\d+"})
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

        return $this->render('competition/list.html.twig', [
            'competitions' => $competitions,
            'search' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="competitions_show", requirements={"id"="\d+"})
     * @ParamConverter("competition", class="App:Competition")
     *
     * @param Competition $competition
     * @return Response
     */
    public function showAction(Competition $competition)
    {
        $com1 = new Comment();
        $com1->setText('Comment1');

            $com11 = new Comment();
            $com11->setText('Comment11');

                $com111 = new Comment();
                $com111->setText('Comment111');

                $com112 = new Comment();
                $com112->setText('Comment112');

            $com12 = new Comment();
            $com12->setText('Comment12');

                $com121 = new Comment();
                $com121->setText('Comment121');

                $com122 = new Comment();
                $com122->setText('Comment122');

        $com2 = new Comment();
        $com2->setText('Comment2');

            $com21 = new Comment();
            $com21->setText('Comment21');

                $com211 = new Comment();
                $com211->setText('Comment211');

                $com212 = new Comment();
                $com212->setText('Comment212');

            $com22 = new Comment();
            $com22->setText('Comment22');

                $com221 = new Comment();
                $com221->setText('Comment221');

                $com222 = new Comment();
                $com222->setText('Comment222');

        $com3 = new Comment();
        $com3->setText('Comment3');

            $com31 = new Comment();
            $com31->setText('Comment31');

                $com311 = new Comment();
                $com311->setText('Comment311');

                $com312 = new Comment();
                $com312->setText('Comment312');

            $com32 = new Comment();
            $com32->setText('Comment32');

                $com321 = new Comment();
                $com321->setText('Comment321');

                $com322 = new Comment();
                $com322->setText('Comment322');


        $com11->addChild($com111);
        $com11->addChild($com112);

        $com12->addChild($com121);
        $com12->addChild($com122);

        $com21->addChild($com211);
        $com21->addChild($com222);

        $com22->addChild($com221);
        $com22->addChild($com222);

        $com31->addChild($com311);
        $com31->addChild($com322);

        $com32->addChild($com321);
        $com32->addChild($com322);

        $com1->addChild($com11);
        $com1->addChild($com12);

        $com2->addChild($com21);
        $com2->addChild($com22);

        $com3->addChild($com31);
        $com3->addChild($com32);

        $comments = [$com1, $com2, $com3];

        return $this->render('competition/show.html.twig', [
            'competition' => $competition,
            'comments' => $comments
        ]);
    }
}