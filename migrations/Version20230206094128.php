<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206094128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, ville_id INT NOT NULL, nom VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_E6E132B4A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typeorg_organisation (id INT AUTO_INCREMENT NOT NULL, organisation_id INT NOT NULL, typeorganisation_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_25FB8A3B9E6B1585 (organisation_id), INDEX IDX_25FB8A3B14D1619E (typeorganisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B4A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE typeorg_organisation ADD CONSTRAINT FK_25FB8A3B9E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE typeorg_organisation ADD CONSTRAINT FK_25FB8A3B14D1619E FOREIGN KEY (typeorganisation_id) REFERENCES typeorganisation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation DROP FOREIGN KEY FK_E6E132B4A73F0036');
        $this->addSql('ALTER TABLE typeorg_organisation DROP FOREIGN KEY FK_25FB8A3B9E6B1585');
        $this->addSql('ALTER TABLE typeorg_organisation DROP FOREIGN KEY FK_25FB8A3B14D1619E');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE typeorg_organisation');
    }
}
