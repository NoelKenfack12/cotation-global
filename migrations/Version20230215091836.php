<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215091836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier ADD paysorigin_id INT DEFAULT NULL, ADD paysprovenance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF22ECD2FEE FOREIGN KEY (paysorigin_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2116CC5DC FOREIGN KEY (paysprovenance_id) REFERENCES pays (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF22ECD2FEE ON panier (paysorigin_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2116CC5DC ON panier (paysprovenance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF22ECD2FEE');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2116CC5DC');
        $this->addSql('DROP INDEX IDX_24CC0DF22ECD2FEE ON panier');
        $this->addSql('DROP INDEX IDX_24CC0DF2116CC5DC ON panier');
        $this->addSql('ALTER TABLE panier DROP paysorigin_id, DROP paysprovenance_id');
    }
}
