# EduSchool
## Application de gestion d'un lycée
(ne pas tenir compte du nom pourri de l'application)

### Message

Le fichier contenant la base de données est à la racine du projet mais il doit être à placer dans /var/

Si jamais grâce à la commande ```symfony console doctrine:fixtures:load``` la base de données peut être alimentée par des valeurs exemples

J'ai compris trop tard la consigne qui était de passer par une API pour faire les rendus par twig, j'ai donc ajouté un dossier /appli_test_api_node/ dans lequel j'ai fait une application en NodeJS pour effectuer des requêtes à l'API de l'application.

J'espère que ça n'impactera pas votre évaluation

### Spécifications de l'application

L'application est en php7.4 et tourne avec symfony 5.10

La base de données utilise sqlite3

NodeJS est en version 18.13

Il existe deux rôles dans cette application, voici comment se connecter à des comptes reliés à ces rôles :
- Professeur (rôle super administrateur): 
    - Nom d'utilisateur : prof
    - Mot de passe : prof
- Élève : 
    - Nom d'utilisateur : eleve
    - Mot de passe : eleve

### Rendu attendu

#### Application PHP Symfony
La majorité des fonctionnalités attendues sont disponibles depuis la navbar lorsque l'on est connecté en tant que professeur :
- Pouvoir insérer des classes
- Pouvoir insérer des matières
- Lister l'ensemble des élèves et pouvoir faire une recherche par nom, prénom ou par classe

La fonctionnalité pour éditer les notes d'un élève est disponible en suivant ce chemin :
comm
`navbar > Gestion des élèves > Clique sur un élève > Accéder aux notes de l'étudiant`

Dans cette page un professeur peut consulter et modifier les notes de l'élève choisi

#### Application NodeJS

Les 4 fonctionnalités demandées sont disponibles dans l'application localisée dans le projet NodeJS ``appli_test_api_node``
