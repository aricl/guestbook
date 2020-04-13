<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413093321 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Made all not-nullable properties of the conference and comment entities nullable. Except the id that is';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE comment ALTER author DROP NOT NULL');
        $this->addSql('ALTER TABLE comment ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE conference ALTER city DROP NOT NULL');
        $this->addSql('ALTER TABLE conference ALTER international DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conference ALTER city SET NOT NULL');
        $this->addSql('ALTER TABLE conference ALTER international SET NOT NULL');
        $this->addSql('ALTER TABLE comment ALTER author SET NOT NULL');
        $this->addSql('ALTER TABLE comment ALTER email SET NOT NULL');
    }
}
