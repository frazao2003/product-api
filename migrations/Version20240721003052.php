<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240721003052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, type_product_id INT NOT NULL, name VARCHAR(255) NOT NULL, cod_lote VARCHAR(20) NOT NULL, expiration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5887B07F ON product (type_product_id)');
        $this->addSql('CREATE TABLE type_product (id INT NOT NULL, type_product VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5887B07F FOREIGN KEY (type_product_id) REFERENCES type_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_product_id_seq CASCADE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD5887B07F');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE type_product');
    }
}
