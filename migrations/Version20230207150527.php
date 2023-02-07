<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207150527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE userorganisation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, organisation_id INT NOT NULL, date DATETIME NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_114749DFA76ED395 (user_id), INDEX IDX_114749DF9E6B1585 (organisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE userorganisation ADD CONSTRAINT FK_114749DFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE userorganisation ADD CONSTRAINT FK_114749DF9E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE user ADD telephone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE userorganisation DROP FOREIGN KEY FK_114749DFA76ED395');
        $this->addSql('ALTER TABLE userorganisation DROP FOREIGN KEY FK_114749DF9E6B1585');
        $this->addSql('DROP TABLE userorganisation');
        $this->addSql('ALTER TABLE user DROP telephone');
    }
}
