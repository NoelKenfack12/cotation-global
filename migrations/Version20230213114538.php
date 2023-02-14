<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213114538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, montant DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_input (id INT AUTO_INCREMENT NOT NULL, forminput_id INT NOT NULL, panier_id INT NOT NULL, date DATETIME NOT NULL, valeur LONGTEXT NOT NULL, INDEX IDX_F1051D6A73669B90 (forminput_id), INDEX IDX_F1051D6AF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier_input ADD CONSTRAINT FK_F1051D6A73669B90 FOREIGN KEY (forminput_id) REFERENCES forminput (id)');
        $this->addSql('ALTER TABLE panier_input ADD CONSTRAINT FK_F1051D6AF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_input DROP FOREIGN KEY FK_F1051D6A73669B90');
        $this->addSql('ALTER TABLE panier_input DROP FOREIGN KEY FK_F1051D6AF77D927C');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_input');
    }
}
