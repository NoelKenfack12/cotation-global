<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201152152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forminput (id INT AUTO_INCREMENT NOT NULL, serviceorganisation_id INT NOT NULL, nom VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, type_input VARCHAR(255) NOT NULL, placeholder VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, required TINYINT(1) NOT NULL, INDEX IDX_112C46DB417415BC (serviceorganisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_form_input (id INT AUTO_INCREMENT NOT NULL, forminput_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_B6068E2473669B90 (forminput_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forminput ADD CONSTRAINT FK_112C46DB417415BC FOREIGN KEY (serviceorganisation_id) REFERENCES serviceorganisation (id)');
        $this->addSql('ALTER TABLE option_form_input ADD CONSTRAINT FK_B6068E2473669B90 FOREIGN KEY (forminput_id) REFERENCES forminput (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forminput DROP FOREIGN KEY FK_112C46DB417415BC');
        $this->addSql('ALTER TABLE option_form_input DROP FOREIGN KEY FK_B6068E2473669B90');
        $this->addSql('DROP TABLE forminput');
        $this->addSql('DROP TABLE option_form_input');
    }
}
