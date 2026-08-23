<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260717111221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien ADD titulo VARCHAR(255) NOT NULL, ADD matricula VARCHAR(100) DEFAULT NULL, ADD habitaciones INT NOT NULL, ADD banos INT NOT NULL, ADD garajes INT NOT NULL, ADD ano_construccion INT DEFAULT NULL, ADD numero_piso INT DEFAULT NULL, ADD area_construida DOUBLE PRECISION DEFAULT NULL, ADD departamento VARCHAR(100) NOT NULL, ADD barrio VARCHAR(150) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP titulo, DROP matricula, DROP habitaciones, DROP banos, DROP garajes, DROP ano_construccion, DROP numero_piso, DROP area_construida, DROP departamento, DROP barrio');
    }
}
