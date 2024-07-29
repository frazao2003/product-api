<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729145310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE inputs_save_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE outputs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inputs_save (id INT NOT NULL, inputs TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inputs_save.inputs IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN inputs_save.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE outputs (id INT NOT NULL, outputs_dto TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN outputs.outputs_dto IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE inputs_save_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE outputs_id_seq CASCADE');
        $this->addSql('DROP TABLE inputs_save');
        $this->addSql('DROP TABLE outputs');
    }
}
