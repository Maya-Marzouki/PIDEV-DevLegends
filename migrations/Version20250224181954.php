<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224181954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_reduction CHANGE code code VARCHAR(255) NOT NULL, CHANGE discount discount DOUBLE PRECISION NOT NULL, CHANGE is_used is_used TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1DA22C5077153098 ON code_reduction (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1DA22C5077153098 ON code_reduction');
        $this->addSql('ALTER TABLE code_reduction CHANGE code code VARCHAR(255) DEFAULT NULL, CHANGE discount discount DOUBLE PRECISION DEFAULT NULL, CHANGE is_used is_used TINYINT(1) DEFAULT NULL');
    }
}
