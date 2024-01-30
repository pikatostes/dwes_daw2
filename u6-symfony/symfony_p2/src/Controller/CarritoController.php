<?php
// src/Controller/CarritoController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarritoController extends AbstractController
{
    #[Route('mostrarCarrito', name: 'carrito')]
    public function index(): Response
    {
        // Implementa la lógica para gestionar el carrito de compras
        // Puedes usar servicios, sesiones, etc.

        return $this->render('carrito/index.html.twig');
    }
}
