<?php
// src/Controller/HomeController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home')]
    public function index(): Response
    {
        $message = 'Hello World!';

        return $this->render('home/index.html.twig', [
            'message' => $message,
        ]);
    }
}
