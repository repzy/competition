<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/", name="admin_user_list")
     */
    public function listAction()
    {
        return $this->render('admin/user/list.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="admin_user_edit")
     */
    public function editAction()
    {
        return $this->render('admin/user/edit.html.twig');
    }
}
