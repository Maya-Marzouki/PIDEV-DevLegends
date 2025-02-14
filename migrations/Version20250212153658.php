<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212153658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE centre CHANGE tel_centre tel_centre VARCHAR(12) NOT NULL');
        $this->addSql('ALTER TABLE contrat ADD photo_contrat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pack ADD photo_pack VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE centre CHANGE tel_centre tel_centre INT NOT NULL');
        $this->addSql('ALTER TABLE contrat DROP photo_contrat');
        $this->addSql('ALTER TABLE pack DROP photo_pack');
    }
}
