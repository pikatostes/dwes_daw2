<?php

// src/Controller/UsersController.php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function index(Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Aquí puedes realizar la lógica de registro, como guardar en un archivo, enviar un correo electrónico, etc.
            
            // Puedes redirigir a una página de éxito o hacer cualquier otra cosa aquí.
            // return $this->redirectToRoute('app_success');
        }

        return $this->render('users/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
