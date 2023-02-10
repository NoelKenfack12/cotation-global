<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210145846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_organisation (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, organisation_id INT NOT NULL, date DATETIME NOT NULL, montant INT DEFAULT NULL, INDEX IDX_C4A6DC9F347EFB (produit_id), INDEX IDX_C4A6DC99E6B1585 (organisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_organisation ADD CONSTRAINT FK_C4A6DC9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_organisation ADD CONSTRAINT FK_C4A6DC99E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_organisation DROP FOREIGN KEY FK_C4A6DC9F347EFB');
        $this->addSql('ALTER TABLE produit_organisation DROP FOREIGN KEY FK_C4A6DC99E6B1585');
        $this->addSql('DROP TABLE produit_organisation');
    }
}
