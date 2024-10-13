<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241006120646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1E54D3B42');
        $this->addSql('DROP INDEX IDX_723705D1E54D3B42 ON transaction');
        $this->addSql('ALTER TABLE transaction CHANGE recurring_transaction_id recurring_transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D15B71D755 FOREIGN KEY (recurring_transaction_id) REFERENCES recurring_transaction (id)');
        $this->addSql('CREATE INDEX IDX_723705D15B71D755 ON transaction (recurring_transaction_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D15B71D755');
        $this->addSql('DROP INDEX IDX_723705D15B71D755 ON transaction');
        $this->addSql('ALTER TABLE transaction CHANGE recurring_transaction_id recurring_transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1E54D3B42 FOREIGN KEY (recurring_transaction_id) REFERENCES recurring_transaction (id)');
        $this->addSql('CREATE INDEX IDX_723705D1E54D3B42 ON transaction (recurring_transaction_id)');
    }
}
