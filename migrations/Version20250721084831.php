<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721084831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent (id_agent INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, telephone VARCHAR(255) DEFAULT NULL, type_de_contrat VARCHAR(50) DEFAULT NULL, date_embauche DATE DEFAULT NULL, PRIMARY KEY(id_agent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bien (id_bien INT AUTO_INCREMENT NOT NULL, id_proprietaire INT DEFAULT NULL, type_de_bien VARCHAR(50) DEFAULT NULL, ville VARCHAR(100) DEFAULT NULL, prix NUMERIC(15, 2) DEFAULT NULL, surface_m2 INT DEFAULT NULL, etat_du_bien VARCHAR(255) DEFAULT NULL, INDEX IDX_45EDC3864A22ECA4 (id_proprietaire), PRIMARY KEY(id_bien)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id_client INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_client)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id_offre INT AUTO_INCREMENT NOT NULL, id_client INT DEFAULT NULL, id_bien INT DEFAULT NULL, prix NUMERIC(10, 2) DEFAULT NULL, date_offre DATE DEFAULT NULL, etat_negociation VARCHAR(255) DEFAULT NULL, INDEX IDX_AF86866FE173B1B8 (id_client), INDEX IDX_AF86866FCECDDF84 (id_bien), PRIMARY KEY(id_offre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaire (id_proprietaire INT AUTO_INCREMENT NOT NULL, nom_proprietaire VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_proprietaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visite (id_visites INT AUTO_INCREMENT NOT NULL, id_agent INT DEFAULT NULL, id_client INT DEFAULT NULL, id_bien INT DEFAULT NULL, date_visite DATE DEFAULT NULL, INDEX IDX_B09C8CBBC80EDDAD (id_agent), INDEX IDX_B09C8CBBE173B1B8 (id_client), INDEX IDX_B09C8CBBCECDDF84 (id_bien), PRIMARY KEY(id_visites)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3864A22ECA4 FOREIGN KEY (id_proprietaire) REFERENCES proprietaire (id_proprietaire)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FE173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FCECDDF84 FOREIGN KEY (id_bien) REFERENCES bien (id_bien)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBC80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id_agent)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBE173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBCECDDF84 FOREIGN KEY (id_bien) REFERENCES bien (id_bien)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC3864A22ECA4');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FE173B1B8');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FCECDDF84');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBC80EDDAD');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBE173B1B8');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBCECDDF84');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE bien');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('DROP TABLE visite');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
