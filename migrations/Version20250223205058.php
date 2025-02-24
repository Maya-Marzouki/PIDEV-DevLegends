<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223205058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation ADD nom_participant VARCHAR(255) NOT NULL, ADD email_participant VARCHAR(255) NOT NULL, ADD date_inscription DATE NOT NULL, DROP nomParticipant, DROP emailParticipant, DROP dateInscription');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation ADD nomParticipant INT NOT NULL, ADD emailParticipant INT NOT NULL, ADD dateInscription INT NOT NULL, DROP nom_participant, DROP email_participant, DROP date_inscription');
    }
}
