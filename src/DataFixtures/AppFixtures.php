<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\Note;
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
        // Utilisateur exemple

        $eleveExemple = new Eleve();
        $eleveExemple->setNom('Élève');
        $eleveExemple->setPrenom('Exemple');
        $eleveExemple->setRoles(['ROLE_ELEVE']);
        $eleveExemple->setNomUtilisateur('eleve');
        $eleveExemple->setPassword($this->passwordHasher->hashPassword($eleveExemple, 'eleve'));
        $manager->persist($eleveExemple);

        $professeurExemple = new Professeur();
        $professeurExemple->setNom('Professeur');
        $professeurExemple->setPrenom('Exemple');
        $professeurExemple->setRoles(['ROLE_PROFESSEUR']);
        $professeurExemple->setNomUtilisateur('prof');
        $professeurExemple->setPassword($this->passwordHasher->hashPassword($professeurExemple, 'prof'));
        $manager->persist($professeurExemple);

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

        // Création des élèves
        $eleves = [];
        for ($index = 1; $index <= 100; $index++) {
            $eleve = new Eleve();
            $eleve->setNomUtilisateur("user$index")
                ->setPrenom("Élève_$index")
                ->setPassword($this->passwordHasher->hashPassword($eleve, 'oui'))
                ->setRoles(['ROLE_ELEVE'])
                ->setNom("Nom_$index")
                ->setClasse($classes[$index % 30]);
            $manager->persist($eleve);
            $eleves[] = $eleve;
        }
        $eleves[] = $eleveExemple;

        // Création des professeurs
        $professeurs = [];
        for ($index = 101; $index <= 111; $index++) {
            $professeur = new Professeur();
            $professeur->setNomUtilisateur("user$index")
                ->setPrenom("Prof_$index")
                ->setPassword($this->passwordHasher->hashPassword($professeur, 'oui'))
                ->setRoles(['ROLE_PROFESSEUR'])
                ->setNom("Nom_$index");
            $manager->persist($professeur);
            $professeurs[] = $professeur;
        }
        $professeurs[] = $professeurExemple;

        // Création des matières
        $matieres = [];
        $nomsMatieres = [
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

        foreach ($nomsMatieres as $nomMatiere) {
            $matiere = new Matiere();
            $matiere->setNom($nomMatiere);
            $manager->persist($matiere);
            $matieres[] = $matiere;
        }

        // Création de notes aléatoires pour les élèves
        $faker = \Faker\Factory::create(); // Utilisation de Faker pour des données aléatoires

        foreach ($eleves as $eleve) {
            for ($i = 0; $i <= 5; $i++) {
                $professeur = $professeurs[array_rand($professeurs)];
                $matiere = $matieres[array_rand($matieres)];
                $note = new Note();
                $note->setEleve($eleve)
                    ->setEvaluateur($professeur)
                    ->setMatiere($matiere)
                    ->setNote($faker->randomFloat(2, 0, 20)) // Note entre 0 et 20 avec 2 décimales
                    ->setCommentaire($faker->sentence());
                $manager->persist($note);
            }
        }
        $manager->flush();
    }
}
