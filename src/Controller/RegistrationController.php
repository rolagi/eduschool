<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Utilisateur;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="registration")
 */
    public function index(UserPasswordHasherInterface $passwordHasher, Request $request)
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('password')->getData() !== $form->get('confirmPassword')->getData()) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            } else {
                $this->addFlash('success', 'Compte créé avec succès !');
            }

            $user = new Eleve();
            $user->setNom(ucfirst($form->get('nom')->getData()));
            $user->setPrenom(ucfirst($form->get('prenom')->getData()));
            $user->setNomUtilisateur($form->get('nomUtilisateur')->getData());

            $mdp = $form->get('password')->getData();
            $mdp = $passwordHasher->hashPassword($user, $mdp);
            $user->setPassword($mdp);

            $user->setRoles(['ROLE_ELEVE']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'registration/index.html.twig',
            ['form' => $form->createView()]
        );

    }
}
