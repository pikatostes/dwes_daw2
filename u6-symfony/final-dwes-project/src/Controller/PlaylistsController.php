<?php

namespace App\Controller;

use App\Entity\Playlists;
use App\Form\Playlists1Type;
use App\Repository\PlaylistsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/playlists')]
class PlaylistsController extends AbstractController
{
    #[Route('/', name: 'app_playlists_index', methods: ['GET'])]
    public function index(PlaylistsRepository $playlistsRepository): Response
    {
        return $this->render('playlists/index.html.twig', [
            'playlists' => $playlistsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_playlists_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $playlist = new Playlists();
        $form = $this->createForm(Playlists1Type::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('app_playlists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('playlists/new.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_playlists_show', methods: ['GET'])]
    public function show(Playlists $playlist): Response
    {
        return $this->render('playlists/show.html.twig', [
            'playlist' => $playlist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_playlists_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Playlists $playlist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Playlists1Type::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_playlists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('playlists/edit.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_playlists_delete', methods: ['POST'])]
    public function delete(Request $request, Playlists $playlist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($playlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_playlists_index', [], Response::HTTP_SEE_OTHER);
    }
}
