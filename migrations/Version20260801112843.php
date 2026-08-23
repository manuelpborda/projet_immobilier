<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260801112843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE estudio_cliente (id INT AUTO_INCREMENT NOT NULL, cedula VARCHAR(50) NOT NULL, nombres_apellidos VARCHAR(150) NOT NULL, estado_civil VARCHAR(50) DEFAULT NULL, genero VARCHAR(50) DEFAULT NULL, celular VARCHAR(50) NOT NULL, correo VARCHAR(100) NOT NULL, profesion VARCHAR(150) DEFAULT NULL, empresa_donde_labora VARCHAR(150) DEFAULT NULL, cargo VARCHAR(100) DEFAULT NULL, tipo_contrato VARCHAR(100) DEFAULT NULL, ingresos_fijos DOUBLE PRECISION DEFAULT NULL, ingresos_variables DOUBLE PRECISION DEFAULT NULL, patrimonio_total DOUBLE PRECISION DEFAULT NULL, deudas_financieras DOUBLE PRECISION DEFAULT NULL, valor_inmueble DOUBLE PRECISION DEFAULT NULL, valor_solicitado DOUBLE PRECISION DEFAULT NULL, linea_credito VARCHAR(50) DEFAULT NULL, fecha_solicitud DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE estudio_cliente');
    }
}
