<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\EleveType;
use App\Form\NoteType;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\MatiereRepository;
use App\Repository\NiveauRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EleveController extends AbstractController
{
    /**
     * @Route("/eleves", name="app_eleve")
     */
    public function index(NiveauRepository $niveauRepository, EleveRepository $eleveRepository, ClasseRepository $classeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');

        $niveaux = $niveauRepository->findAll();

        return $this->render('eleve/index.html.twig', [
            'controller_name' => 'EleveController',
            'niveaux' => $niveaux,
        ]);
    }

    /**
     * @Route("/eleves/{id}", name="app_eleve_show")
     */
    public function show(EleveRepository $eleveRepository, Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');

        $eleve = $eleveRepository->find($id);

        $form = $this->createForm(EleveType::class, $eleve);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Les informations de l\'élève ont été mises à jour.');
            return $this->redirectToRoute('edit_eleve', ['id' => $eleve->getId()]);
        }

        return $this->render('eleve/show.html.twig', [
            'controller_name' => 'EleveController',
            'form' => $form->createView(),
            'eleve' => $eleve,
        ]);
    }

    /**
     * @Route("/eleves/{id}/edit", name="edit_eleve")
     */
    public function edit(EleveRepository $eleveRepository, Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');

        $eleve = $eleveRepository->find($id);

        if (!$eleve) {
            throw $this->createNotFoundException('Élève non trouvé.');
        }

        $form = $this->createForm(EleveType::class, $eleve);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Les informations de l\'élève ont été mises à jour.');

            return $this->redirectToRoute('edit_eleve', ['id' => $eleve->getId()]);
        }

        return $this->render('eleve/show.html.twig', [
            'controller_name' => 'EleveController',
            'form' => $form->createView(),
            'eleve' => $eleve,
        ]);
    }

    /**
     * @Route("/eleves/{id}/notes",name="app_eleve_notes")
     */
    public function showNotes(EleveRepository $eleveRepository, Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $eleve = $eleveRepository->find($id);

        if ($this->isGranted('ROLE_PROFESSEUR') || ($this->getUser() && $this->getUser()->getUserIdentifier() === $eleve->getUserIdentifier())) {

            $note = new Note();
            $note->setEleve($eleve);
            $form = $this->createForm(NoteType::class, $note);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($note);
                $entityManager->flush();
                $this->addFlash('success', 'Les informations de l\'élève ont été mises à jour.');
                return $this->redirectToRoute('app_eleve_notes', ['id' => $eleve->getId()]);
            }

            return $this->render('eleve/notes.html.twig', [
                'controller_name' => 'EleveController',
                'form' => $form->createView(),
                'eleve' => $eleve,
                'notes' => $eleve->getNotes(),
            ]);
        } else {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }
    }
}
