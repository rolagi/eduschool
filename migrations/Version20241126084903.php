<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126084903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eleve (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, classe_id INTEGER DEFAULT NULL, nom_utilisateur VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, CONSTRAINT FK_ECA105F78F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ECA105F7D37CC8AC ON eleve (nom_utilisateur)');
        $this->addSql('CREATE INDEX IDX_ECA105F78F5EA509 ON eleve (classe_id)');
        $this->addSql('CREATE TABLE note (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, evaluateur_id INTEGER DEFAULT NULL, eleve_id INTEGER DEFAULT NULL, matiere_id INTEGER DEFAULT NULL, note DOUBLE PRECISION NOT NULL, CONSTRAINT FK_CFBDFA14231F139 FOREIGN KEY (evaluateur_id) REFERENCES professeur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CFBDFA14A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CFBDFA14F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14231F139 ON note (evaluateur_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14A6CC7B2 ON note (eleve_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14F46CD258 ON note (matiere_id)');
        $this->addSql('CREATE TABLE professeur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_utilisateur VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17A55299D37CC8AC ON professeur (nom_utilisateur)');
        $this->addSql('CREATE TABLE utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_utilisateur VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3D37CC8AC ON utilisateur (nom_utilisateur)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__matiere AS SELECT id, nom FROM matiere');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('CREATE TABLE matiere (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO matiere (id, nom) SELECT id, nom FROM __temp__matiere');
        $this->addSql('DROP TABLE __temp__matiere');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9014574A6C6E55B5 ON matiere (nom)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE professeur');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('CREATE TEMPORARY TABLE __temp__matiere AS SELECT id, nom FROM matiere');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('CREATE TABLE matiere (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO matiere (id, nom) SELECT id, nom FROM __temp__matiere');
        $this->addSql('DROP TABLE __temp__matiere');
    }
}
