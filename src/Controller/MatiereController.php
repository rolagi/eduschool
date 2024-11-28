<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatiereController extends AbstractController
{
    /**
     * @Route("/matieres", name="app_matiere")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, MatiereRepository $matiereRepository): Response
    {

        $matieres = $matiereRepository->findAll();

        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($matiere);
            $entityManager->flush();

            $this->addFlash('success', 'La matière a été ajoutée avec succès.');

            return $this->redirectToRoute('app_matiere');
        }

        return $this->render('matiere/index.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
        ]);
    }

    /**
     * @Route("/matieres/{id}/delete", name="app_matiere_delete")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, MatiereRepository $matiereRepository, int $id): Response
    {
        $matiere = $matiereRepository->find($id);

        foreach($matiere->getNotes() as $note){
            $note->setMatiere(null);
        }

        $entityManager->remove($matiere);
        $entityManager->flush();

        return $this->redirectToRoute('app_matiere');
    }
}
