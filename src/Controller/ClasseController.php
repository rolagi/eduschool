<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClasseController extends AbstractController
{
    /**
     * @Route("/classes", name="app_classe")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, ClasseRepository $classeRepository): Response
    {
        $classes = $classeRepository->findAll();

        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash('success', 'La classe a été ajoutée avec succès.');

            return $this->redirectToRoute('app_classe');
        }

        return $this->render('classe/index.html.twig', [
            'form' => $form->createView(),
            'classes' => $classes,
        ]);
    }

    /**
     * @Route("/classes/{id}/delete", name="app_classe_delete")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ClasseRepository $classeRepository, int $id): Response
    {
        $classe = $classeRepository->find($id);

        $entityManager->remove($classe);
        $entityManager->flush();

        return $this->redirectToRoute('app_classe');
    }
}
