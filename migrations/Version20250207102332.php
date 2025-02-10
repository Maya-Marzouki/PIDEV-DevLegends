<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207102332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles_conseils (id INT AUTO_INCREMENT NOT NULL, titre_article VARCHAR(255) NOT NULL, contenu_article VARCHAR(255) NOT NULL, categorie_mental_article VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, sujet_avis VARCHAR(255) NOT NULL, contenu_avis VARCHAR(255) NOT NULL, note_avis INT NOT NULL, date_avis DATE NOT NULL, email_avis VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE centre (id INT AUTO_INCREMENT NOT NULL, nom_centre VARCHAR(255) NOT NULL, adresse_centre VARCHAR(255) NOT NULL, tel_centre INT NOT NULL, email_centre VARCHAR(255) NOT NULL, specialite_centre VARCHAR(255) NOT NULL, capacite_centre INT NOT NULL, photo_centre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, date_cons DATE NOT NULL, lien_visio_cons VARCHAR(255) NOT NULL, score_mental INT NOT NULL, etat_mental VARCHAR(255) NOT NULL, notes_cons VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, centre_id INT DEFAULT NULL, datdeb_cont DATE NOT NULL, datfin_cont DATE NOT NULL, modpaiment_cont VARCHAR(255) NOT NULL, renouv_auto_cont TINYINT(1) NOT NULL, INDEX IDX_60349993463CD7C3 (centre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, titre_event VARCHAR(255) NOT NULL, date_event DATE NOT NULL, lieu_event VARCHAR(255) NOT NULL, statut_event VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, titre_for VARCHAR(255) NOT NULL, date_for DATE NOT NULL, lieu_for VARCHAR(255) NOT NULL, statut_for VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack (id INT AUTO_INCREMENT NOT NULL, nom_pack VARCHAR(255) NOT NULL, descript_pack VARCHAR(255) NOT NULL, prix_pack DOUBLE PRECISION NOT NULL, duree_pack VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_produit_id INT DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, prix_produit DOUBLE PRECISION NOT NULL, qte_produit INT NOT NULL, statut_produit VARCHAR(255) NOT NULL, INDEX IDX_29A5EC2791FDB457 (categorie_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, consultation_q_id INT DEFAULT NULL, question_quiz VARCHAR(255) NOT NULL, categorie_sant VARCHAR(255) NOT NULL, reponse_quiz VARCHAR(255) NOT NULL, score_quiz INT NOT NULL, INDEX IDX_A412FA92400E840D (consultation_q_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, sujet_rec VARCHAR(255) NOT NULL, contenu_rec VARCHAR(255) NOT NULL, date_rec DATE NOT NULL, email_des VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(20) NOT NULL, last_name VARCHAR(20) NOT NULL, user_email VARCHAR(30) NOT NULL, pswrd VARCHAR(10) NOT NULL, user_role VARCHAR(20) NOT NULL, user_age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993463CD7C3 FOREIGN KEY (centre_id) REFERENCES centre (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2791FDB457 FOREIGN KEY (categorie_produit_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92400E840D FOREIGN KEY (consultation_q_id) REFERENCES consultation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993463CD7C3');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2791FDB457');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92400E840D');
        $this->addSql('DROP TABLE articles_conseils');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE centre');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE pack');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
