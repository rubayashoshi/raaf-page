<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150403000354 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE ad_link (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, INDEX IDX_8E63CB94549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE property_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, INDEX IDX_E01FBE6A549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ad_type (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE properties (id INT AUTO_INCREMENT NOT NULL, property_type_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_available DATE NOT NULL, rent NUMERIC(10, 0) NOT NULL, available_to_couple TINYINT(1) NOT NULL, is_agent TINYINT(1) NOT NULL, contact_phone TINYINT(1) NOT NULL, contact_email TINYINT(1) NOT NULL, contact_name VARCHAR(255) NOT NULL, rent_period enum('monthly', 'weekly'), INDEX IDX_87C331C79C81C6EB (property_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ad_type_property_rel (property_id INT NOT NULL, adtype_id INT NOT NULL, INDEX IDX_DD2987CE549213EC (property_id), INDEX IDX_DD2987CEA1907CE5 (adtype_id), PRIMARY KEY(property_id, adtype_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");

        $this->addSql("ALTER TABLE ad_link ADD CONSTRAINT FK_8E63CB94549213EC FOREIGN KEY (property_id) REFERENCES properties (id)");
        $this->addSql("ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A549213EC FOREIGN KEY (property_id) REFERENCES properties (id)");
        $this->addSql("ALTER TABLE properties ADD CONSTRAINT FK_87C331C79C81C6EB FOREIGN KEY (property_type_id) REFERENCES property_type (id)");
        $this->addSql("ALTER TABLE ad_type_property_rel ADD CONSTRAINT FK_DD2987CE549213EC FOREIGN KEY (property_id) REFERENCES properties (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ad_type_property_rel ADD CONSTRAINT FK_DD2987CEA1907CE5 FOREIGN KEY (adtype_id) REFERENCES ad_type (id) ON DELETE CASCADE");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE properties DROP FOREIGN KEY FK_87C331C79C81C6EB");
        $this->addSql("ALTER TABLE ad_type_property_rel DROP FOREIGN KEY FK_DD2987CEA1907CE5");
        $this->addSql("ALTER TABLE ad_link DROP FOREIGN KEY FK_8E63CB94549213EC");
        $this->addSql("ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A549213EC");
        $this->addSql("ALTER TABLE ad_type_property_rel DROP FOREIGN KEY FK_DD2987CE549213EC");

        $this->addSql("DROP TABLE ad_link");
        $this->addSql("DROP TABLE property_type");
        $this->addSql("DROP TABLE images");
        $this->addSql("DROP TABLE ad_type");
        $this->addSql("DROP TABLE properties");
        $this->addSql("DROP TABLE ad_type_property_rel");
    }
}
