<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131013171201 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Client ADD source_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE Client ADD CONSTRAINT FK_C0E80163953C1C61 FOREIGN KEY (source_id) REFERENCES Source (id)");
        $this->addSql("CREATE INDEX IDX_C0E80163953C1C61 ON Client (source_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Client DROP FOREIGN KEY FK_C0E80163953C1C61");
        $this->addSql("DROP INDEX IDX_C0E80163953C1C61 ON Client");
        $this->addSql("ALTER TABLE Client DROP source_id");
    }
}
