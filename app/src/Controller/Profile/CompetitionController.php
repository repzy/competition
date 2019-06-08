<?php

namespace App\Controller\Profile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/competitons")
 * @IsGranted("ROLE_USER")
 */
class CompetitionController
{
    public function __construct()
    {
    }

    /**
     * @Route("/", name="profile_competitions_list")
     */
    public function listAction()
    {

    }

    /**
     * @Route("/new", name="profile_competitions_new")
     */
    public function newAction()
    {

    }

    /**
     * @Route("/edit/{id}", name="profile_competetions_edit")
     */
    public function editAction()
    {

    }

    /**
     * @Route("/delete/{id}", name="profile_competitions_delete")
     */
    public function deleteAction()
    {

    }
}
