<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131004215753 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE TerritoryHistory (id INT AUTO_INCREMENT NOT NULL, territory_id INT DEFAULT NULL, user_id INT DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_on DATE DEFAULT NULL, checked_in_on DATE DEFAULT NULL, INDEX IDX_930E55AD73F74AD4 (territory_id), INDEX IDX_930E55ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE TerritoryHistory ADD CONSTRAINT FK_930E55AD73F74AD4 FOREIGN KEY (territory_id) REFERENCES Territory (id)");
        $this->addSql("ALTER TABLE TerritoryHistory ADD CONSTRAINT FK_930E55ADA76ED395 FOREIGN KEY (user_id) REFERENCES User (id)");
        $this->addSql("ALTER TABLE Territory DROP checkout_out_by, DROP checkout_on, DROP checkin_on");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE TerritoryHistory");
        $this->addSql("ALTER TABLE Territory ADD checkout_out_by INT DEFAULT NULL, ADD checkout_on DATE DEFAULT NULL, ADD checkin_on DATE DEFAULT NULL");
    }
}
