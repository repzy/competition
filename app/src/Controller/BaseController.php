<?php

namespace App\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl("competitions_list"));
    }

    /**
     * @Route("/email")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailerAction()
    {
        return $this->render("emails/resetting.html.twig", [
            'url' => $this->generateUrl('resetting_change', [
                'code' => Uuid::uuid4()->toString()
            ])
        ]);
    }
}