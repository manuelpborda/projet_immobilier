<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260912101301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE testimonio ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE testimonio ADD CONSTRAINT FK_3643AB02DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3643AB02DB38439E ON testimonio (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE testimonio DROP FOREIGN KEY FK_3643AB02DB38439E');
        $this->addSql('DROP INDEX IDX_3643AB02DB38439E ON testimonio');
        $this->addSql('ALTER TABLE testimonio DROP usuario_id');
    }
}
