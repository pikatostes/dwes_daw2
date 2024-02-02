<?php
// src/Controller/CarritoController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Producto;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class CarritoController extends AbstractController
{
    #[Route('mostrarCarrito', name: 'carrito')]
    public function index(Request $request): Response
    {
        $carrito = $request->getSession()->get('carrito', []);

        return $this->render('producto/carrito.html.twig', ['carrito' => $carrito]);
    }

    #[Route('añadir/{id}', name: 'añadir')]
    public function añadir(EntityManagerInterface $entityManager, $id, Request $request): Response
    {
        // Aquí deberías obtener la información del producto correspondiente al $id desde tu fuente de datos (base de datos, etc.).
        // Por ahora, asumiré que tienes una entidad llamada Producto con un repositorio.

        $producto = $entityManager->getRepository(Producto::class)->find($id);

        // Verificar si el producto existe
        if (!$producto) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        // Obtener la información del producto
        $nombre = $producto->getNombre();
        $descripcion = $producto->getDescripcion();
        $code = $producto->getCode();
        $stock = $producto->getStock();

        // Obtener el carrito actual
        $carrito = $request->getSession()->get('carrito', []);

        // Verificar si el producto ya está en el carrito
        if (isset($carrito[$id])) {
            // Si el producto ya está en el carrito, puedes decidir si incrementar la cantidad, mostrar un mensaje, etc.
            // Por ahora, simplemente redirigir al carrito.
            return $this->redirectToRoute('carrito');
        }

        // Agregar el producto al carrito
        $carrito[$id] = [
            'id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'code' => $code,
            'stock' => $stock,
        ];

        // Actualizar el carrito en la sesión
        $request->getSession()->set('carrito', $carrito);

        // Redirigir al carrito
        return $this->redirectToRoute('carrito');
    }

    #[Route('eliminar/{id}', name: 'eliminarCarrito')]
    public function eliminar($id, Request $request): Response
    {
        // Obtener el carrito actual
        $carrito = $request->getSession()->get('carrito', []);

        // Verificar si el producto está en el carrito
        if (isset($carrito[$id])) {
            // Eliminar el producto del carrito
            unset($carrito[$id]);

            // Actualizar el carrito en la sesión
            $request->getSession()->set('carrito', $carrito);
        }

        // Redirigir al carrito
        return $this->redirectToRoute('carrito');
    }
}
