<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\SongType;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/song')]
class SongController extends AbstractController
{
    #[Route('/', name: 'app_song_index', methods: ['GET'])]
    public function index(SongRepository $songRepository): Response
    {
        return $this->render('song/index.html.twig', [
            'songs' => $songRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_song_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Redirige a la página deseada para usuarios que no tienen el rol ROLE_ADMIN
            return $this->redirectToRoute('app_denied');
        }
        
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('cover')->getData();
            if ($file !== null) {
                // Generar un nombre único para el archivo
                $fileName = uniqid() . '.' . $file->guessExtension();
                // Almacenar el archivo en una ubicación segura
                $file->move($this->getParameter('images_directory'), $fileName);
                // Guardar el nombre del archivo en la propiedad del usuario
                $song->setCover($fileName);
            }

            $file = $form->get('audio')->getData();
            if ($file !== null) {
                // Generar un nombre único para el archivo
                $fileName = uniqid() . '.mp3';
                // Almacenar el archivo en una ubicación segura
                $file->move($this->getParameter('music_directory'), $fileName);
                // Guardar el nombre del archivo en la propiedad del usuario
                $song->setAudio($fileName);
            }

            $entityManager->persist($song);
            $entityManager->flush();

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('song/new.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_song_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(Song $song): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Redirige a la página deseada para usuarios que no tienen el rol ROLE_ADMIN
            return $this->redirectToRoute('app_denied');
        }

        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_song_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Song $song, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Redirige a la página deseada para usuarios que no tienen el rol ROLE_ADMIN
            return $this->redirectToRoute('app_denied');
        }

        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('cover')->getData();
            if ($file !== null) {
                // Generar un nombre único para el archivo
                $fileName = uniqid() . '.' . $file->guessExtension();
                // Almacenar el archivo en una ubicación segura
                $file->move($this->getParameter('images_directory'), $fileName);
                // Guardar el nombre del archivo en la propiedad del usuario
                $song->setCover($fileName);
            }

            $file = $form->get('audio')->getData();
            if ($file !== null) {
                // Generar un nombre único para el archivo
                $fileName = uniqid() . '.mp3';
                // Almacenar el archivo en una ubicación segura
                $file->move($this->getParameter('music_directory'), $fileName);
                // Guardar el nombre del archivo en la propiedad del usuario
                $song->setAudio($fileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('song/edit.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_song_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Song $song, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Redirige a la página deseada para usuarios que no tienen el rol ROLE_ADMIN
            return $this->redirectToRoute('app_denied');
        }

        if ($this->isCsrfTokenValid('delete' . $song->getId(), $request->request->get('_token'))) {
            $entityManager->remove($song);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
    }
}
