<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260718133303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documentos_propietarios (id INT AUTO_INCREMENT NOT NULL, propietario_id INT NOT NULL, nombre_original VARCHAR(255) NOT NULL, nombre_archivo VARCHAR(255) NOT NULL, tipo_documento VARCHAR(50) NOT NULL, fecha_subida DATETIME NOT NULL, INDEX IDX_27CA98053C8D32C (propietario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documentos_propietarios ADD CONSTRAINT FK_27CA98053C8D32C FOREIGN KEY (propietario_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documentos_propietarios DROP FOREIGN KEY FK_27CA98053C8D32C');
        $this->addSql('DROP TABLE documentos_propietarios');
    }
}
