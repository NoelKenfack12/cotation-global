<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201120735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE serviceorganisation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typeorg_serviceorg (id INT AUTO_INCREMENT NOT NULL, serviceorganisation_id INT NOT NULL, typeorganisation_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_A3E96E89417415BC (serviceorganisation_id), INDEX IDX_A3E96E8914D1619E (typeorganisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE typeorg_serviceorg ADD CONSTRAINT FK_A3E96E89417415BC FOREIGN KEY (serviceorganisation_id) REFERENCES serviceorganisation (id)');
        $this->addSql('ALTER TABLE typeorg_serviceorg ADD CONSTRAINT FK_A3E96E8914D1619E FOREIGN KEY (typeorganisation_id) REFERENCES typeorganisation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE typeorg_serviceorg DROP FOREIGN KEY FK_A3E96E89417415BC');
        $this->addSql('ALTER TABLE typeorg_serviceorg DROP FOREIGN KEY FK_A3E96E8914D1619E');
        $this->addSql('DROP TABLE serviceorganisation');
        $this->addSql('DROP TABLE typeorg_serviceorg');
    }
}
