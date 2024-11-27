<?php

namespace App\Controller;

use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    /**
     * @Route("/notes/{id}/delete", name="app_note_delete")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, NoteRepository $noteRepository, int $id): Response
    {
        $note = $noteRepository->find($id);

        $entityManager->remove($note);
        $entityManager->flush();

        return $this->redirectToRoute('app_eleve_notes', ['id' => $note->getEleve()->getId()]);
    }

    /**
     * @Route("/notes/{id}", name="app_note_show")
     */
    public function show(NoteRepository $noteRepository, Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $note = $noteRepository->find($id);

        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_eleve_notes', ['id' => $note->getEleve()->getId()]);
        }

        return $this->render('note/show.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
        ]);

    }
}
