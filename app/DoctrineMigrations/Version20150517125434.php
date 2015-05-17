<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150517125434 extends AbstractMigration
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

//        $this->addSql("INSERT INTO `category` (`id`,`name`, `display`) VALUES" .
//            "(1, 'Property', 'Property')," .
//            "(2, 'Vehicles', 'Vehicles')," .
//            "(3, 'Electronics', 'Electronics')," .
//            "(4, 'Service', 'Service')," .
//            "(5, 'Job', 'Job');
//        ");

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `category_id`, `parent_id`,`level`) VALUES" .
            "(1, 'To Rent', 'To Rent', 1, NULL, 1),".
            "(2, 'To Share', 'To Share', 1, NULL, 1),".
            "(3, 'For Sale', 'For Sale', 1, NULL, 1)"
        );

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `category_id`, `parent_id`,`level`) VALUES" .
            "(4, 'Offered', 'Offered', 1, 1, 2),".
            "(5, 'Wanted', 'Wanted', 1, 1, 2)"
        );

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `category_id`, `parent_id`,`level`) VALUES" .
            "(7, 'Offered', 'Offered', 1, 2, 2),".
            "(8, 'Wanted', 'Wanted', 1, 2, 2)"
        );

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `category_id`, `parent_id`,`level`) VALUES" .
            "(9, 'Offered', 'Offered', 1, 3, 2),".
            "(10, 'Wanted', 'Wanted', 1, 3, 2)"
        );

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `category_id`, `parent_id`,`level`) VALUES" .
            "(11, 'Studio Flat', 'Studio Flat', 1, 4, 3),".
            "(12, '1 bedroom', '1 bedroom', 1, 4, 3),".
            "(13, '2 bedrooms', '2 bedrooms', 1, 4, 3),".
            "(14, '3 bedrooms', '3 bedrooms', 1, 4, 3),".
            "(15, '4 bedrooms', '4 bedrooms', 1, 4, 3),".
            "(16, '5 bedrooms', '5 bedrooms', 1, 4, 3),".
            "(17, '5+ bedrooms', '5+ bedrooms', 1, 4, 3)");

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `category_id`, `parent_id`,`level`) VALUES" .
            "(18, 'Studio Flat', 'Studio Flat', 1, 5, 3),".
            "(19, '1 bedroom', '1 bedroom', 1, 5, 3),".
            "(20, '2 bedrooms', '2 bedrooms', 1, 5, 3),".
            "(21, '3 bedrooms', '3 bedrooms', 1, 5, 3),".
            "(22, '4 bedrooms', '4 bedrooms', 1, 5, 3),".
            "(23, '5 bedrooms', '5 bedrooms', 1, 5, 3),".
            "(24, '5+ bedrooms', '5+ bedrooms', 1, 5, 3)");

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `category_id`, `parent_id`,`level`) VALUES" .
            "(25, 'Studio Flat', 'Studio Flat', 1, 7, 3),".
            "(26, '1 bedroom', '1 bedroom', 1, 7, 3),".
            "(27, '2 bedrooms', '2 bedrooms', 1, 7, 3),".
            "(28, '3 bedrooms', '3 bedrooms', 1, 7, 3),".
            "(29, '4 bedrooms', '4 bedrooms', 1, 7, 3),".
            "(30, '5 bedrooms', '5 bedrooms', 1, 7, 3),".
            "(31, '5+ bedrooms', '5+ bedrooms', 1, 7, 3)");

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `category_id`, `parent_id`,`level`) VALUES" .
            "(32, 'Studio Flat', 'Studio Flat', 1, 8, 3),".
            "(33, '1 bedroom', '1 bedroom', 1, 8, 3),".
            "(34, '2 bedrooms', '2 bedrooms', 1, 8, 3),".
            "(35, '3 bedrooms', '3 bedrooms', 1, 8, 3),".
            "(36, '4 bedrooms', '4 bedrooms', 1, 8, 3),".
            "(37, '5 bedrooms', '5 bedrooms', 1, 8, 3),".
            "(38, '5+ bedrooms', '5+ bedrooms', 1, 8, 3)");
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

        $this->addSql('TRUNCATE TABLE `sub_category`');
        //$this->addSql('TRUNCATE TABLE `category`');
    }
}
