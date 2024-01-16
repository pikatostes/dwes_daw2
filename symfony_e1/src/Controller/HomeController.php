<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    #[Route('/home')]
    public function index(): Response
    {
        $message = 'Hello World!';

        return new Response($message);
    }
}