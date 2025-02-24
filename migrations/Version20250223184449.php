<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223184449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92400E840D');
        $this->addSql('DROP INDEX IDX_A412FA92400E840D ON quiz');
        $this->addSql('ALTER TABLE quiz DROP consultation_q_id, DROP name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz ADD consultation_q_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92400E840D FOREIGN KEY (consultation_q_id) REFERENCES consultation (id)');
        $this->addSql('CREATE INDEX IDX_A412FA92400E840D ON quiz (consultation_q_id)');
    }
}
