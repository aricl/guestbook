<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200411125716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Change Conference properties to match book';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE conference ADD international BOOLEAN NOT NULL DEFAULT false');
        $this->addSql('ALTER TABLE conference DROP author');
        $this->addSql('ALTER TABLE conference DROP text');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conference ADD author VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE conference ADD text VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE conference DROP international');
    }
}
