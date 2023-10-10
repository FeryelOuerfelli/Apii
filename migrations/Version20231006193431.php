<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231006193431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, affectation_id INT DEFAULT NULL, num_serie VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, date_achat DATE DEFAULT NULL, etat VARCHAR(255) NOT NULL, imagem VARCHAR(255) DEFAULT NULL, INDEX IDX_18D2B0916D0ABA22 (affectation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0916D0ABA22 FOREIGN KEY (affectation_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0916D0ABA22');
        $this->addSql('DROP TABLE materiel');
    }
}
