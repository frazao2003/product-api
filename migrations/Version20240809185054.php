<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809185054 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stock_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inputs_save (id INT NOT NULL, inputs TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inputs_save.inputs IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN inputs_save.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE outputs (id INT NOT NULL, outputs_dto TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN outputs.outputs_dto IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, type_product_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04AD5887B07F ON product (type_product_id)');
        $this->addSql('CREATE TABLE stock_product (id INT NOT NULL, product_id INT DEFAULT NULL, quant INT NOT NULL, cod_lote VARCHAR(255) NOT NULL, expiration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAEC140E4584665A ON stock_product (product_id)');
        $this->addSql('CREATE TABLE type_product (id INT NOT NULL, type_product VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5887B07F FOREIGN KEY (type_product_id) REFERENCES type_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_product ADD CONSTRAINT FK_CAEC140E4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE inputs_save_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE outputs_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stock_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_product_id_seq CASCADE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD5887B07F');
        $this->addSql('ALTER TABLE stock_product DROP CONSTRAINT FK_CAEC140E4584665A');
        $this->addSql('DROP TABLE inputs_save');
        $this->addSql('DROP TABLE outputs');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE stock_product');
        $this->addSql('DROP TABLE type_product');
    }
}
