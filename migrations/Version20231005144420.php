<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005144420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, groupe_age VARCHAR(255) NOT NULL, INDEX IDX_497DD634642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, fiche_medicale_id INT DEFAULT NULL, ordonnance_id INT DEFAULT NULL, poids DOUBLE PRECISION NOT NULL, taille DOUBLE PRECISION NOT NULL, imc DOUBLE PRECISION NOT NULL, temperature DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, pression_arterielle DOUBLE PRECISION NOT NULL, frequence_cardiaque DOUBLE PRECISION NOT NULL, taux_glycemie DOUBLE PRECISION NOT NULL, symptomes VARCHAR(255) NOT NULL, traitement VARCHAR(255) NOT NULL, observation VARCHAR(255) NOT NULL, INDEX IDX_964685A69A99F4BC (fiche_medicale_id), UNIQUE INDEX UNIQ_964685A62BF23B8F (ordonnance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, pharmacie_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, image_signature VARCHAR(255) NOT NULL, num_facture INT NOT NULL, INDEX IDX_FE866410BC6D351B (pharmacie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_assurance (id INT AUTO_INCREMENT NOT NULL, ordonnance_id INT DEFAULT NULL, pharmacien_id INT DEFAULT NULL, assureur_id INT DEFAULT NULL, remboursement_id INT DEFAULT NULL, num_adherent INT NOT NULL, description VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, image_facture VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BF0694462BF23B8F (ordonnance_id), INDEX IDX_BF069446CFDB96BE (pharmacien_id), INDEX IDX_BF06944680F7E20A (assureur_id), UNIQUE INDEX UNIQ_BF069446F61EE8B (remboursement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_medicale (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, allergies VARCHAR(255) NOT NULL, pathologie VARCHAR(255) NOT NULL, date_de_creation DATE NOT NULL, date_de_modification DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, code_ordonnance VARCHAR(255) NOT NULL, medicaments VARCHAR(255) NOT NULL, dosage VARCHAR(255) NOT NULL, nombre_jours INT NOT NULL, date_de_creation DATE NOT NULL, date_de_modification DATE NOT NULL, UNIQUE INDEX UNIQ_924B326C7F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacie (id INT AUTO_INCREMENT NOT NULL, pharmacien_id INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, horaire VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, matricule VARCHAR(255) NOT NULL, services VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5FC19434CFDB96BE (pharmacien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, medecin_id INT DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, etat VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, date_de_creation DATE NOT NULL, date_de_modification DATE NOT NULL, INDEX IDX_D499BFF64F31A84 (medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, etat VARCHAR(255) NOT NULL, quantite DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE remboursement (id INT AUTO_INCREMENT NOT NULL, montant_a_rembourser DOUBLE PRECISION NOT NULL, montant_maximale DOUBLE PRECISION NOT NULL, taux_remboursement DOUBLE PRECISION NOT NULL, date_remboursement DATE NOT NULL, etat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, consultation_id INT DEFAULT NULL, planning_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, symptomes VARCHAR(255) NOT NULL, date_de_creation DATE NOT NULL, date DATE NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, INDEX IDX_65E8AA0A6B899279 (patient_id), UNIQUE INDEX UNIQ_65E8AA0A62FF6CDF (consultation_id), INDEX IDX_65E8AA0A3D865311 (planning_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, num_tel VARCHAR(255) DEFAULT NULL, age VARCHAR(255) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, specialite VARCHAR(255) DEFAULT NULL, date_de_creation DATE NOT NULL, cin VARCHAR(255) DEFAULT NULL, num_securite_sociale INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, date_de_modification DATE DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, is_blocked TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A69A99F4BC FOREIGN KEY (fiche_medicale_id) REFERENCES fiche_medicale (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A62BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410BC6D351B FOREIGN KEY (pharmacie_id) REFERENCES pharmacie (id)');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF0694462BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id)');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF069446CFDB96BE FOREIGN KEY (pharmacien_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF06944680F7E20A FOREIGN KEY (assureur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF069446F61EE8B FOREIGN KEY (remboursement_id) REFERENCES remboursement (id)');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE pharmacie ADD CONSTRAINT FK_5FC19434CFDB96BE FOREIGN KEY (pharmacien_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF64F31A84 FOREIGN KEY (medecin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A3D865311 FOREIGN KEY (planning_id) REFERENCES planning (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634642B8210');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A69A99F4BC');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A62BF23B8F');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410BC6D351B');
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF0694462BF23B8F');
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF069446CFDB96BE');
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF06944680F7E20A');
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF069446F61EE8B');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C7F2DEE08');
        $this->addSql('ALTER TABLE pharmacie DROP FOREIGN KEY FK_5FC19434CFDB96BE');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF64F31A84');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A62FF6CDF');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A3D865311');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE fiche_assurance');
        $this->addSql('DROP TABLE fiche_medicale');
        $this->addSql('DROP TABLE ordonnance');
        $this->addSql('DROP TABLE pharmacie');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE remboursement');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
