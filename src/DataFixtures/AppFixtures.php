<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\Professeur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        // Niveaux
        $niveau2 = new Niveau();
        $niveau2->setNom('Seconde');
        $manager->persist($niveau2);

        $niveau1 = new Niveau();
        $niveau1->setNom('Première');
        $manager->persist($niveau1);

        $niveauT = new Niveau();
        $niveauT->setNom('Terminale');
        $manager->persist($niveauT);

        // Classes

        $classes = [];
        for ($i = 1; $i <= 30; $i++) {
            $classe = new Classe();
            switch ($i % 3) {
                case 0:
                    $classe->setNom('2-' . $i);
                    $classe->setNiveau($niveau2);
                    break;
                case 1:
                    $classe->setNom('1-' . $i);
                    $classe->setNiveau($niveau1);
                    break;
                case 2:
                    $classe->setNom('T-' . $i);
                    $classe->setNiveau($niveauT);
                    break;
            }
            $classes[] = $classe;
            $manager->persist($classe);
        }

        for ($index = 1; $index <= 100; $index++) {
            $user = new Eleve();
            $user->setNomUtilisateur("user$index")
                ->setPrenom("Prénom $index")
                ->setPassword($this->passwordHasher->hashPassword($user, 'oui'))
                ->setRoles(['ROLE_ELEVE'])
                ->setNom("Nom $index")
                ->setClasse($classes[$index % 30]);
            $manager->persist($user);
        }

        for ($index = 101; $index <= 111; $index++) {
            $user = new Professeur();
            $user->setNomUtilisateur("user$index")
                ->setPrenom("Prénom $index")
                ->setPassword($this->passwordHasher->hashPassword($user, 'oui'))
                ->setRoles(['ROLE_PROFESSEUR'])
                ->setNom("Nom $index");
            $manager->persist($user);
        }

        $matieres = [
            'Mathématiques',
            'Physique',
            'Chimie',
            'Biologie',
            'Histoire',
            'Géographie',
            'Français',
            'Anglais',
            'Espagnol',
            'Informatique',
            'Philosophie',
            'Éducation Civique',
            'Arts Plastiques',
            'Musique',
            'Technologie',
        ];

        foreach ($matieres as $nomMatiere) {
            $matiere = new Matiere();
            $matiere->setNom($nomMatiere);

            $manager->persist($matiere);
        }

        $manager->flush();
    }
}
