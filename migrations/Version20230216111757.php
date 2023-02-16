<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216111757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produitpanier (id INT AUTO_INCREMENT NOT NULL, produit_organisation_id INT NOT NULL, panier_id INT NOT NULL, date DATETIME NOT NULL, quantite DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION DEFAULT NULL, taxe DOUBLE PRECISION DEFAULT NULL, INDEX IDX_9E6886FABE65D597 (produit_organisation_id), INDEX IDX_9E6886FAF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produitpanier ADD CONSTRAINT FK_9E6886FABE65D597 FOREIGN KEY (produit_organisation_id) REFERENCES produit_organisation (id)');
        $this->addSql('ALTER TABLE produitpanier ADD CONSTRAINT FK_9E6886FAF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produitpanier DROP FOREIGN KEY FK_9E6886FABE65D597');
        $this->addSql('ALTER TABLE produitpanier DROP FOREIGN KEY FK_9E6886FAF77D927C');
        $this->addSql('DROP TABLE produitpanier');
    }
}
