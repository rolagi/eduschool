<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Form\ProfesseurType;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfesseurController extends AbstractController
{
    /**
     * @Route("/professeur", name="app_professeur")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, ProfesseurRepository $professeurRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $professeurs = $professeurRepository->findAll();

        $professeur = new Professeur();
        $form = $this->createForm(ProfesseurType::class, $professeur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $professeur->setPassword($passwordHasher->hashPassword($professeur, $professeur->getPlainPassword()));
            $professeur->eraseCredentials();

            $professeur->setRoles(['ROLE_PROFESSEUR']);

            $entityManager->persist($professeur);
            $entityManager->flush();

            $this->addFlash('success', 'Le professeur a été ajouté avec succès.');

            return $this->redirectToRoute('app_professeur');
        }

        return $this->render('professeur/index.html.twig', [
            'form' => $form->createView(),
            'professeurs' => $professeurs,
        ]);
    }


    /**
     * @Route("/professeur/ajouter", name="app_professeur_ajouter")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $professeur = new Professeur();
        $form = $this->createForm(ProfesseurType::class, $professeur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($professeur);
            $entityManager->flush();

            $this->addFlash('success', 'Le professeur a été ajouté avec succès.');
            return $this->redirectToRoute('app_professeur_ajouter');
        }

        return $this->render('professeur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/professeur/supprimer/{id}", name="app_professeur_delete")
     */
    public function supprimer(int $id, ProfesseurRepository $professeurRepository, EntityManagerInterface $entityManager): Response
    {
        $professeur = $professeurRepository->find($id);

        if (!$professeur) {
            $this->addFlash('error', 'Professeur introuvable.');
            return $this->redirectToRoute('app_professeur_ajouter');
        }

        $entityManager->remove($professeur);
        $entityManager->flush();

        $this->addFlash('success', 'Le professeur a été supprimé avec succès.');
        return $this->redirectToRoute('app_professeur');
    }
}
