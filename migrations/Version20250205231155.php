<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205231155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles_conseils (id INT AUTO_INCREMENT NOT NULL, titre_article VARCHAR(255) NOT NULL, contenu_article VARCHAR(255) NOT NULL, categorie_mental_article VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE centre (id INT AUTO_INCREMENT NOT NULL, nom_centre VARCHAR(255) NOT NULL, adresse_centre VARCHAR(255) NOT NULL, tel_centre INT NOT NULL, email_centre VARCHAR(255) NOT NULL, specialite_centre VARCHAR(255) NOT NULL, capacite_centre INT NOT NULL, photo_centre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, date_cons DATE NOT NULL, lien_visio_cons VARCHAR(255) NOT NULL, score_mental INT NOT NULL, etat_mental VARCHAR(255) NOT NULL, notes_cons VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, centre_id INT DEFAULT NULL, datdeb_cont DATE NOT NULL, datfin_cont DATE NOT NULL, modpaiment_cont VARCHAR(255) NOT NULL, renouv_auto_cont TINYINT(1) NOT NULL, INDEX IDX_60349993463CD7C3 (centre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack (id INT AUTO_INCREMENT NOT NULL, nom_pack VARCHAR(255) NOT NULL, descript_pack VARCHAR(255) NOT NULL, prix_pack DOUBLE PRECISION NOT NULL, duree_pack VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, consultation_q_id INT DEFAULT NULL, question_quiz VARCHAR(255) NOT NULL, categorie_sant VARCHAR(255) NOT NULL, reponse_quiz VARCHAR(255) NOT NULL, score_quiz INT NOT NULL, INDEX IDX_A412FA92400E840D (consultation_q_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993463CD7C3 FOREIGN KEY (centre_id) REFERENCES centre (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92400E840D FOREIGN KEY (consultation_q_id) REFERENCES consultation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993463CD7C3');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92400E840D');
        $this->addSql('DROP TABLE articles_conseils');
        $this->addSql('DROP TABLE centre');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE pack');
        $this->addSql('DROP TABLE quiz');
    }
}
