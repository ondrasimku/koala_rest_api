<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028143920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character (id SERIAL NOT NULL, name TEXT NOT NULL, gender TEXT DEFAULT NULL, ability TEXT NOT NULL, minimal_distance NUMERIC(10, 2) NOT NULL, weight NUMERIC(10, 2) DEFAULT NULL, born TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, in_space_since TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, beer_consumption INT NOT NULL, knows_the_answer BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE nemesis (id SERIAL NOT NULL, character_id INT DEFAULT NULL, is_alive BOOLEAN NOT NULL, years INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5802E4831136BE75 ON nemesis (character_id)');
        $this->addSql('CREATE TABLE secret (id SERIAL NOT NULL, nemesis_id INT NOT NULL, secret_code BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5CA2E8E5512E3775 ON secret (nemesis_id)');
        $this->addSql('ALTER TABLE nemesis ADD CONSTRAINT FK_5802E4831136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E5512E3775 FOREIGN KEY (nemesis_id) REFERENCES nemesis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE nemesis DROP CONSTRAINT FK_5802E4831136BE75');
        $this->addSql('ALTER TABLE secret DROP CONSTRAINT FK_5CA2E8E5512E3775');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE nemesis');
        $this->addSql('DROP TABLE secret');
    }
}
