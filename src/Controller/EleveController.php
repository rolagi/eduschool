<?php

namespace App\Controller;

use App\Repository\ClasseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EleveController extends AbstractController
{
    #[Route('/eleves', name: 'app_eleve')]
    public function index(ClasseRepository $classeRepository): Response
    {

        $classes = $classeRepository->findAll();
dd($classes);
        return $this->render('eleve/index.html.twig', [
            'controller_name' => 'EleveController',
            'classes'=> $classes,
        ]);
    }
}
