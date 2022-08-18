<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="home")
     */
    public function home(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/home.html.twig');
    }
}