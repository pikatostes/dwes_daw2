<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/{path}', name: 'app_not_found', requirements: ['path' => '.+'], priority: -1)]
    public function notFound($path): Response
    {
        $content = $this->renderView('error/index.html.twig', ['path' => $path]);
        return new Response($content, Response::HTTP_NOT_FOUND);
    }
}
