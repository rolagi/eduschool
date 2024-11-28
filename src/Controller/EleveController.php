<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\EleveType;
use App\Form\NoteType;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @Route("/eleves/search", name="app_eleve_search")
     */
    public function search(Request $request, EleveRepository $eleveRepository, ClasseRepository $classeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');

        $eleves = $eleveRepository->findAll();
        $classes = $classeRepository->findAll();

        return $this->render('eleve/search.html.twig', [
            'eleves' => $eleves,
            'classes' => $classes,
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

    /**
     * @Route("/api/eleves/search", name="api_eleve_search")
     */
    public function apiSearch(Request $request, EleveRepository $eleveRepository, SerializerInterface $serializer): JsonResponse
    {
        $name = $request->get("name");
        $classe = $request->get("classe");

        if (!$name && !$classe) {
            return new JsonResponse(['error' => 'Au moins un critère de recherche est requis'], 400);
        }

        $resultat = $eleveRepository->findByNameAndClasse($name, $classe);

        $data = $serializer->serialize($resultat, 'json', ['groups' => 'eleve:read']);

        return new JsonResponse($data, 200, [], true);
    }
}
