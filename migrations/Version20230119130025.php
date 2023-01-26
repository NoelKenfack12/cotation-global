<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119130025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE continent (id INT AUTO_INCREMENT NOT NULL, src VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, nom VARCHAR(100) NOT NULL, citoyen VARCHAR(100) DEFAULT NULL, citoyenne VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_6CC70C7C6C6E55B5 (nom), UNIQUE INDEX UNIQ_6CC70C7C8E7EF6AC (citoyen), UNIQUE INDEX UNIQ_6CC70C7CD113EBB3 (citoyenne), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drapeau (id INT AUTO_INCREMENT NOT NULL, src VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, drapeau_id INT DEFAULT NULL, continent_id INT NOT NULL, nom VARCHAR(255) NOT NULL, siteweb VARCHAR(255) DEFAULT NULL, citoyen VARCHAR(255) DEFAULT NULL, citoyenne VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, domaine VARCHAR(255) DEFAULT NULL, src VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_349F3CAE6C6E55B5 (nom), UNIQUE INDEX UNIQ_349F3CAE8535EB27 (siteweb), UNIQUE INDEX UNIQ_349F3CAE77153098 (code), UNIQUE INDEX UNIQ_349F3CAE78AF0ACC (domaine), UNIQUE INDEX UNIQ_349F3CAE2C70DBB9 (drapeau_id), INDEX IDX_349F3CAE921F4C77 (continent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', dateins DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pays ADD CONSTRAINT FK_349F3CAE2C70DBB9 FOREIGN KEY (drapeau_id) REFERENCES drapeau (id)');
        $this->addSql('ALTER TABLE pays ADD CONSTRAINT FK_349F3CAE921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pays DROP FOREIGN KEY FK_349F3CAE2C70DBB9');
        $this->addSql('ALTER TABLE pays DROP FOREIGN KEY FK_349F3CAE921F4C77');
        $this->addSql('DROP TABLE continent');
        $this->addSql('DROP TABLE drapeau');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
