<?php
// src/Controller/GreetingController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GreetingController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        $name = $request->query->get("greet", "invitado");

        return $this->render('greeting/index.html.twig', [
            'nombre' => $name,
        ]);
    }
}
