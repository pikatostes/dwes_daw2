<?php
// src/Controller/HomeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Producto; // Asegúrate de importar la entidad Producto
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Obtener la lista de productos desde la base de datos
        $productos = $entityManager->getRepository(Producto::class)->findAll();

        return $this->render('producto/lista.html.twig', [
            'productos' => $productos,
        ]);
    }

    // #[Route("/{any}")]
    // public function noEncontrada(): Response
    // {
    //     return $this->render('error/notFound.html.twig');
    // }
}
