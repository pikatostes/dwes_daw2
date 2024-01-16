<?php

// src/Controller/GreetingController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GreetingController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        $greet = "";

        if ($name = $request->query->get("greet")) {
            $greet = sprintf('<h1>Hello %s!</h1>', htmlspecialchars($name));
        } else {
            $greet = '<h1>Muy buenas invitado</h1>';
        }

        return new Response($greet);
    }
}