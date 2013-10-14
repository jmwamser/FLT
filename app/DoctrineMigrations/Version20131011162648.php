<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131011162648 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE TerritoryHistory");
        $this->addSql("ALTER TABLE Territory ADD checked_out_to INT DEFAULT NULL, ADD status INT DEFAULT NULL, ADD checked_out_on DATETIME DEFAULT NULL, ADD checked_in_on DATETIME DEFAULT NULL");
        $this->addSql("ALTER TABLE Territory ADD CONSTRAINT FK_6B85BBC56F8C7119 FOREIGN KEY (checked_out_to) REFERENCES User (id)");
        $this->addSql("CREATE INDEX IDX_6B85BBC56F8C7119 ON Territory (checked_out_to)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE TerritoryHistory (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, territory_id INT DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_on DATE DEFAULT NULL, checked_in_on DATE DEFAULT NULL, INDEX IDX_930E55AD73F74AD4 (territory_id), INDEX IDX_930E55ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE TerritoryHistory ADD CONSTRAINT FK_930E55ADA76ED395 FOREIGN KEY (user_id) REFERENCES User (id)");
        $this->addSql("ALTER TABLE TerritoryHistory ADD CONSTRAINT FK_930E55AD73F74AD4 FOREIGN KEY (territory_id) REFERENCES Territory (id)");
        $this->addSql("ALTER TABLE Territory DROP FOREIGN KEY FK_6B85BBC56F8C7119");
        $this->addSql("DROP INDEX IDX_6B85BBC56F8C7119 ON Territory");
        $this->addSql("ALTER TABLE Territory DROP checked_out_to, DROP status, DROP checked_out_on, DROP checked_in_on");
    }
}
