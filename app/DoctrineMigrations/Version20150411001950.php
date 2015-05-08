<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150411001950 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE properties ADD user_id INT DEFAULT NULL, CHANGE rent_period rent_period enum('monthly', 'weekly')");
        $this->addSql("ALTER TABLE properties ADD CONSTRAINT FK_87C331C7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)");
        $this->addSql("CREATE INDEX IDX_87C331C7A76ED395 ON properties (user_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE properties DROP FOREIGN KEY FK_87C331C7A76ED395");
        $this->addSql("DROP INDEX IDX_87C331C7A76ED395 ON properties");
        $this->addSql("ALTER TABLE properties DROP user_id, CHANGE rent_period rent_period VARCHAR(255) DEFAULT NULL");
    }
}
