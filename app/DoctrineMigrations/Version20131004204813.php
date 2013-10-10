<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131004204813 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Client CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE last_visit last_visit DATETIME DEFAULT NULL, CHANGE street_number street_number VARCHAR(255) DEFAULT NULL, CHANGE street_name street_name VARCHAR(255) DEFAULT NULL, CHANGE neighborhood neighborhood VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE zip zip VARCHAR(255) DEFAULT NULL, CHANGE territory territory INT DEFAULT NULL, CHANGE lat lat NUMERIC(10, 0) DEFAULT NULL, CHANGE lon lon NUMERIC(10, 0) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Client CHANGE first_name first_name VARCHAR(255) NOT NULL, CHANGE last_name last_name VARCHAR(255) NOT NULL, CHANGE last_visit last_visit DATETIME NOT NULL, CHANGE street_number street_number VARCHAR(255) NOT NULL, CHANGE street_name street_name VARCHAR(255) NOT NULL, CHANGE neighborhood neighborhood VARCHAR(255) NOT NULL, CHANGE city city VARCHAR(255) NOT NULL, CHANGE zip zip VARCHAR(255) NOT NULL, CHANGE territory territory INT NOT NULL, CHANGE lat lat NUMERIC(10, 0) NOT NULL, CHANGE lon lon NUMERIC(10, 0) NOT NULL");
    }
}
