<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home')]
    public function index(): Response
    {
        return new Response(
            <<<EOF
                <html>
                    <body>
                        <h1>Hola</h1>
                    </body>
                </html>
            EOF
        );
    }
}
