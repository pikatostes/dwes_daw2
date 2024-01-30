<?php
// src/Controller/ProductoController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Producto;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProductoType;

class ProductoController extends AbstractController
{
    #[Route('crearProducto', name: 'crear')]
    public function crearProducto(Request $request, EntityManagerInterface $em): Response
    {
        $producto = new Producto();

        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($producto);
            $em->flush();

            return $this->redirectToRoute('listar');
        }

        return $this->render('producto/crear.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('listarProductos', name: 'listar')]
    public function listarProductos(EntityManagerInterface $em): Response
    {
        $productos = $em->getRepository(Producto::class)->findAll();

        return $this->render('producto/lista.html.twig', [
            'productos' => $productos,
        ]);
    }

    #[Route('eliminarProducto/{id}', name: 'eliminar')]
    public function eliminarProducto($id, EntityManagerInterface $em): Response
    {
        $producto = $em->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'No se encontró el producto con id '.$id
            );
        }

        $em->remove($producto);
        $em->flush();

        // Redirigir a la lista de productos después de eliminar
        return $this->redirectToRoute('listar');
    }
}