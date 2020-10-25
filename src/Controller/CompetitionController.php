<?php

namespace App\Controller;

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
     * @Route("", name="competitions_list")
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

        $data['page'] = max(1, $data['page']);

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
        return $this->render('competition/show.html.twig', [
            'competition' => $competition,
        ]);
    }
}
