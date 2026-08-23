<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260610181514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBC80EDDAD');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FE173B1B8');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBE173B1B8');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC3864A22ECA4');
        $this->addSql('CREATE TABLE favorito (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, bien_id INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_881067C7A76ED395 (user_id), INDEX IDX_881067C7BD95B80F (bien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, type_user VARCHAR(20) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorito ADD CONSTRAINT FK_881067C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorito ADD CONSTRAINT FK_881067C7BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id_bien)');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC3864A22ECA4');
        $this->addSql('ALTER TABLE bien ADD foto VARCHAR(255) DEFAULT NULL, ADD estrato INT DEFAULT NULL, ADD prix_administration NUMERIC(12, 2) DEFAULT NULL, ADD caracteristiques_flexibles JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3864A22ECA4 FOREIGN KEY (id_proprietaire) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contact_message ADD fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE phone phone VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FE173B1B8');
        $this->addSql('ALTER TABLE offre ADD conditions_achat LONGTEXT DEFAULT NULL, CHANGE prix prix NUMERIC(15, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FE173B1B8 FOREIGN KEY (id_client) REFERENCES user (id)');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBC80EDDAD');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBE173B1B8');
        $this->addSql('ALTER TABLE visite ADD statut VARCHAR(50) DEFAULT NULL, ADD commentaires LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBC80EDDAD FOREIGN KEY (id_agent) REFERENCES user (id)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBE173B1B8 FOREIGN KEY (id_client) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC3864A22ECA4');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FE173B1B8');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBC80EDDAD');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBE173B1B8');
        $this->addSql('CREATE TABLE agent (id_agent INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL, telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type_de_contrat VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_embauche DATE DEFAULT NULL, PRIMARY KEY(id_agent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE client (id_client INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id_client)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proprietaire (id_proprietaire INT AUTO_INCREMENT NOT NULL, nom_proprietaire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id_proprietaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favorito DROP FOREIGN KEY FK_881067C7A76ED395');
        $this->addSql('ALTER TABLE favorito DROP FOREIGN KEY FK_881067C7BD95B80F');
        $this->addSql('DROP TABLE favorito');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC3864A22ECA4');
        $this->addSql('ALTER TABLE bien DROP foto, DROP estrato, DROP prix_administration, DROP caracteristiques_flexibles');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3864A22ECA4 FOREIGN KEY (id_proprietaire) REFERENCES proprietaire (id_proprietaire)');
        $this->addSql('ALTER TABLE contact_message DROP fecha_envio, CHANGE phone phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FE173B1B8');
        $this->addSql('ALTER TABLE offre DROP conditions_achat, CHANGE prix prix NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FE173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBC80EDDAD');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBE173B1B8');
        $this->addSql('ALTER TABLE visite DROP statut, DROP commentaires');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBC80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id_agent)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBE173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
    }
}
