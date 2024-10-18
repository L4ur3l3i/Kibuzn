<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018195119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recurring_transaction ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE recurring_transaction ADD CONSTRAINT FK_D3509AA6C54C8C93 FOREIGN KEY (type_id) REFERENCES operation_type (id)');
        $this->addSql('CREATE INDEX IDX_D3509AA6C54C8C93 ON recurring_transaction (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recurring_transaction DROP FOREIGN KEY FK_D3509AA6C54C8C93');
        $this->addSql('DROP INDEX IDX_D3509AA6C54C8C93 ON recurring_transaction');
        $this->addSql('ALTER TABLE recurring_transaction DROP type_id');
    }
}
