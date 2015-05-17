<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150517115434 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE sub_category (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, ' .
            'parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, display VARCHAR(255) NOT NULL, ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `category` (`id` INT AUTO_INCREMENT NOT NULL, `name` VARCHAR(255) NOT NULL, ' .
            '`display` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE ' .
            'utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `sub_category` ADD CONSTRAINT FK_723649C912469DE2 FOREIGN KEY ' .
            '(category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE `sub_category` ADD CONSTRAINT FK_723649C9727ACA70 FOREIGN KEY (`parent_id`) ' .
            'REFERENCES sub_category (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE `sub_category` DROP FOREIGN KEY FK_723649C9727ACA70');
        $this->addSql('ALTER TABLE `sub_category` DROP FOREIGN KEY FK_723649C912469DE2');
        $this->addSql('DROP TABLE sub_category');
        $this->addSql('DROP TABLE category');
    }
}
