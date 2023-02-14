<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213130728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier ADD serviceorganisation_id INT DEFAULT NULL, ADD typeorganisation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2417415BC FOREIGN KEY (serviceorganisation_id) REFERENCES serviceorganisation (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF214D1619E FOREIGN KEY (typeorganisation_id) REFERENCES typeorganisation (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2417415BC ON panier (serviceorganisation_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF214D1619E ON panier (typeorganisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2417415BC');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF214D1619E');
        $this->addSql('DROP INDEX IDX_24CC0DF2417415BC ON panier');
        $this->addSql('DROP INDEX IDX_24CC0DF214D1619E ON panier');
        $this->addSql('ALTER TABLE panier DROP serviceorganisation_id, DROP typeorganisation_id');
    }
}
