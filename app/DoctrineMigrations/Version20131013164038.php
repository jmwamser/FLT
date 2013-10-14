<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131013164038 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Client CHANGE territory territory_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE Client ADD CONSTRAINT FK_C0E8016373F74AD4 FOREIGN KEY (territory_id) REFERENCES Territory (id)");
        $this->addSql("CREATE INDEX IDX_C0E8016373F74AD4 ON Client (territory_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Client DROP FOREIGN KEY FK_C0E8016373F74AD4");
        $this->addSql("DROP INDEX IDX_C0E8016373F74AD4 ON Client");
        $this->addSql("ALTER TABLE Client CHANGE territory_id territory INT DEFAULT NULL");
    }
}
