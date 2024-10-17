<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017184729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recurring_transaction ADD start_date DATE NOT NULL, ADD interval_type VARCHAR(20) NOT NULL, ADD interval_value INT NOT NULL, DROP recurrence_interval, CHANGE recurrence_value iterations INT DEFAULT NULL, CHANGE recurrence_end_date end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction DROP recurrent, CHANGE recurrence_number iteration_number INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recurring_transaction ADD recurrence_interval VARCHAR(30) NOT NULL, DROP start_date, DROP interval_type, DROP interval_value, CHANGE iterations recurrence_value INT DEFAULT NULL, CHANGE end_date recurrence_end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD recurrent TINYINT(1) NOT NULL, CHANGE iteration_number recurrence_number INT DEFAULT NULL');
    }
}
