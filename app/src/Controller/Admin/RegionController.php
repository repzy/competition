<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/region")
 * @IsGranted("ROLE_ADMIN")
 */
class RegionController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/", name="admin_region_list")
     */
    public function listAction()
    {
        return $this->render('admin/region/list.html.twig');
    }

    /**
     * @Route("/new", name="admin_region_new")
     */
    public function newAction()
    {
        return $this->render('admin/region/edit.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="admin_region_edit")
     */
    public function editAction()
    {
        return $this->render('admin/region/edit.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="admin_region_delete")
     */
    public function deleteAction()
    {

    }
}
