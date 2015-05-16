<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150515233039 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        $this->addSql("CREATE TABLE ad_status (`id` INT AUTO_INCREMENT NOT NULL, `name` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("INSERT INTO ad_status (`name`) VALUES ('draft'),('live'),('archived')");
        $this->addSql("ALTER TABLE properties ADD `status_id` INT NOT NULL DEFAULT 1");
        $this->addSql("ALTER TABLE properties ADD CONSTRAINT FK_87C331C76BF700BD FOREIGN KEY (status_id) REFERENCES ad_status (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        $this->addSql("ALTER TABLE properties DROP FOREIGN KEY FK_87C331C76BF700BD");
        $this->addSql("DROP TABLE ad_status");
        $this->addSql("ALTER TABLE properties DROP status_id");
    }
}
