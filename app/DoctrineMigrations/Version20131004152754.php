<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131004152754 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Territory CHANGE coords coords LONGTEXT DEFAULT NULL, CHANGE map map VARCHAR(255) DEFAULT NULL, CHANGE checkout_out_by checkout_out_by INT DEFAULT NULL, CHANGE checkout_on checkout_on DATE DEFAULT NULL, CHANGE checkin_on checkin_on DATE DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Territory CHANGE coords coords LONGTEXT NOT NULL, CHANGE map map VARCHAR(255) NOT NULL, CHANGE checkout_out_by checkout_out_by INT NOT NULL, CHANGE checkout_on checkout_on DATE NOT NULL, CHANGE checkin_on checkin_on DATE NOT NULL");
    }
}
