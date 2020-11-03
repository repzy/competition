<?php

namespace App\Controller;

use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BaseController extends AbstractController
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
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl("competitions_list"));
    }

    /**
     * @Route("/sitemap.xml")
     */
    public function sitemapAction()
    {
        $competitions = $this->competitionRepository->findAllIds();

        $urlset = new \SimpleXMLElement('<urlset></urlset>');
        $urlset->addAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');

        $url = $urlset->addChild('url');
        $url->addChild('loc', $this->generateUrl(
            'competitions_list',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
        $url->addChild('priority', 1.0);

        foreach ($competitions as $competition) {
            $url = $urlset->addChild('url');
            $url->addChild('loc', $this->generateUrl(
                'competitions_show',
                ['id' => $competition['id']],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
            $url->addChild('priority', 0.8);
        }

        return new Response(
            $urlset->asXML(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/xml'
            ]
        );
    }
}
