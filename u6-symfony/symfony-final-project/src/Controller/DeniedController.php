<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeniedController extends AbstractController
{
    #[Route('/denied', name: 'app_denied')]
    public function index(): Response
    {
        return $this->render('denied/index.html.twig', [
            'controller_name' => 'DeniedController',
        ]);
    }
}
