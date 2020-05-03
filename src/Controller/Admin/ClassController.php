<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/classes")
 * @IsGranted("ROLE_ADMIN")
 */
class ClassController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/", name="admin_classes_list")
     */
    public function listAction()
    {
        return $this->render('admin/class/list.html.twig');
    }

    /**
     * @Route("/new", name="admin_classes_new")
     */
    public function newAction()
    {
        return $this->render('admin/class/edit.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="admin_classes_edit")
     */
    public function editAction()
    {
        return $this->render('admin/class/edit.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="admin_classes_delete")
     */
    public function deleteAction()
    {

    }
}
