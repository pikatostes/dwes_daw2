<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/about')]
    public function about(): Response
    {

        return $this->render('/about.html.twig', [
            'title' => 'About Us!',
        ]);

    }

    #[Route('/contact')]
    public function contact(): Response
    {
        return $this->render('/contact.html.twig', [
            'title' => 'Contact!',
        ]);
    }
}