<?php

namespace App\Controller;

use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\NiveauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EleveController extends AbstractController
{
    #[Route('/eleves', name: 'app_eleve')]
    public function index(NiveauRepository $niveauRepository, ClasseRepository $classeRepository): Response
    {
        $niveaux = $niveauRepository->findAll();

        return $this->render('eleve/index.html.twig', [
            'controller_name' => 'EleveController',
            'niveaux'=> $niveaux,
        ]);
    }

    #[Route('/eleve/{id}', name: 'app_eleve_show')]
    public function show(EleveRepository $eleveRepository): Response
    {
        $eleve  = $eleveRepository->find($id);

        return $this->render('eleve/index.html.twig', [
            'controller_name' => 'EleveController',
            'eleve'=> $eleve,
        ]);
    }
}
