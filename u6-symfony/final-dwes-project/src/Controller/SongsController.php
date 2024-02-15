<?php

namespace App\Controller;

use App\Entity\Songs;
use App\Form\SongsType;
use App\Repository\SongsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/songs')]
class SongsController extends AbstractController
{
    #[Route('/', name: 'app_songs_index', methods: ['GET'])]
    public function index(SongsRepository $songsRepository): Response
    {
        return $this->render('songs/index.html.twig', [
            'songs' => $songsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_songs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $song = new Songs();
        $form = $this->createForm(SongsType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($song);
            $entityManager->flush();

            return $this->redirectToRoute('app_songs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('songs/new.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_songs_show', methods: ['GET'])]
    public function show(Songs $song): Response
    {
        return $this->render('songs/show.html.twig', [
            'song' => $song,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_songs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Songs $song, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SongsType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_songs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('songs/edit.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_songs_delete', methods: ['POST'])]
    public function delete(Request $request, Songs $song, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))) {
            $entityManager->remove($song);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_songs_index', [], Response::HTTP_SEE_OTHER);
    }
}
