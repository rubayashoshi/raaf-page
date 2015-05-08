<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150403162306 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE ad_link ADD link VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE images ADD name VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE ad_type ADD name VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE properties CHANGE rent_period rent_period enum('monthly', 'weekly')");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE ad_link DROP `link`");
        $this->addSql("ALTER TABLE ad_type DROP `name`");
        $this->addSql("ALTER TABLE images DROP `name`, DROP `address`");
        $this->addSql("ALTER TABLE `properties` CHANGE `rent_period` `rent_period` VARCHAR(255) DEFAULT NULL");
    }
}
