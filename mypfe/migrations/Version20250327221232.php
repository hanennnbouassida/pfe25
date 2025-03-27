<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327221232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business ADD ville VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE client ADD ville VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product ADD qte VARCHAR(255) NOT NULL, ADD stock_stats VARCHAR(255) DEFAULT \'available\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business DROP ville');
        $this->addSql('ALTER TABLE client DROP ville');
        $this->addSql('ALTER TABLE product DROP qte, DROP stock_stats');
    }
}
