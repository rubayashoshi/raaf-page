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
        /*
        $this->addSql("INSERT INTO `category` (`id`,`name`, `display`) VALUES" .
            "(1, 'Property', 'Property')," .
            "(2, 'Vehicles', 'Vehicles')," .
            "(3, 'Electronics', 'Electronics')," .
            "(4, 'Service', 'Service')," .
            "(5, 'Job', 'Job');
        ");
        */
        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(1, 'To Rent', 'To Rent', 'houses-and-flats-to-rent', 1, NULL, 1),".
            "(2, 'To Share', 'To Share', 'houses-and-flats-to-share', 1, NULL, 1),".
            "(3, 'For Sale', 'For Sale', 'houses-and-flats-for-sale', 1, NULL, 1)"
        );

        //rent
        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(4, 'Offered', 'Offered', 'houses-and-flats-rent-offer', 1, 1, 2),".
            "(5, 'Wanted', 'Wanted', 'houses-and-flats-rent-wanted', 1, 1, 2)"
        );

        //share
        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(7, 'Offered', 'Offered', 'houses-and-flats-share-offered', 1, 2, 2),".
            "(8, 'Wanted', 'Wanted', 'houses-and-flats-share-wanted', 1, 2, 2)"
        );

        //sale
        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(9, 'Offered', 'Offered', 'houses-and-flats-sale-offered', 1, 3, 2),".
            "(10, 'Wanted', 'Wanted', 'houses-and-flats-sale-wanted', 1, 3, 2)"
        );

        //rent
        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(11, 'Studio Flat', 'Studio Flat', 'studio-flat-to-rent-offer', 1, 4, 3),".
            "(12, '1 bedroom', '1 bedroom', '1-bed-room-flat-to-rent-offer', 1, 4, 3),".
            "(13, '2 bedrooms', '2 bedrooms', '1-bed-room-flats-and-houses-to-rent-offer', 1, 4, 3),".
            "(14, '3 bedrooms', '3 bedrooms', '2-bed-room-flats-and-houses-to-rent-offer', 1, 4, 3),".
            "(15, '4 bedrooms', '4 bedrooms', '3-bed-room-flats-and-houses-to-rent-offer', 1, 4, 3),".
            "(16, '5 bedrooms', '5 bedrooms', '4-bed-room-flats-and-houses-to-rent-offer', 1, 4, 3),".
            "(17, '5 or more bedrooms', '5 or more bedrooms', '5-or-more-bed-room-houses-to-rent-offer', 1, 4, 3);");

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(18, 'Studio Flat', 'Studio Flat', 'studio-flats-to-rent-wanted', 1, 5, 3),".
            "(19, '1 bedroom', '1 bedroom', '1-bed-room-flats-and-houses-to-rent-wanted', 1, 5, 3),".
            "(20, '2 bedrooms', '2 bedrooms', '2-bed-room-flats-and-houses-to-rent-wanted', 1, 5, 3),".
            "(21, '3 bedrooms', '3 bedrooms', '3-bed-room-flats-and-houses-to-rent-wanted', 1, 5, 3),".
            "(22, '4 bedrooms', '4 bedrooms', '4-bed-room-and-houses-to-rent-wanted', 1, 5, 3),".
            "(23, '5 bedrooms', '5 bedrooms', '5-bed-room-and-houses-to-rent-wanted', 1, 5, 3),".
            "(24, '5+ bedrooms', '5 or more bedrooms', 'more-than-5-bed-room-and-houses-to-rent-wanted', 1, 5, 3)");

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(25, 'Studio Flat', 'Studio Flat', 'studio-flats-to-share-offer', 1, 7, 3),".
            "(26, '1 bedroom', '1 bedroom', '1-bed-room-flats-and-houses-to-share-offer', 1, 7, 3),".
            "(27, '2 bedrooms', '2 bedrooms', '2-bed-room-flats-and-houses-to-share-offer', 1, 7, 3),".
            "(28, '3 bedrooms', '3 bedrooms', '3-bed-room-flats-and-houses-to-share-offer', 1, 7, 3),".
            "(29, '4 bedrooms', '4 bedrooms', '4-bed-room-houses-to-share-offer', 1, 7, 3),".
            "(30, '5 bedrooms', '5 bedrooms', '5-bed-room-houses-to-share-offer', 1, 7, 3),".
            "(31, '5+ bedrooms', '5 or more bedrooms', 'more-than-5-bed-room-houses-to-share-offer', 1, 7, 3)");

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(32, 'Studio Flat', 'Studio Flat', 'studio-flats-to-share-wanted', 1, 8, 3),".
            "(33, '1 bedroom', '1 bedroom', '1-bed-room-flat-and-houses-to-share-wanted', 1, 8, 3),".
            "(34, '2 bedrooms', '2 bedrooms', '2-bed-room-flats-and-houses-to-share-wanted', 1, 8, 3),".
            "(35, '3 bedrooms', '3 bedrooms', '3-bed-room-flats-and-houses-to-share-wanted', 1, 8, 3),".
            "(36, '4 bedrooms', '4 bedrooms', '4-bed-room-houses-to-share-wanted', 1, 8, 3),".
            "(37, '5 bedrooms', '5 bedrooms', '5-bed-room-houses-to-share-wanted', 1, 8, 3),".
            "(38, '5+ bedrooms', '5 or more bedrooms', 'more-than-5-bed-room-flats-and-houses-to-share-wanted', 1, 8, 3)");

        //sale offer and wanted
        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(39, 'Studio Flat', 'Studio Flat', 'studio-flats-to-sale-offer', 1, 9, 3),".
            "(40, '1 bedroom', '1 bedroom', '1-bed-room-flats-and-houses-to-sale-offer', 1, 9, 3),".
            "(41, '2 bedrooms', '2 bedrooms', '2-bed-room-flats-and-houses-to-sale-offer', 1, 9, 3),".
            "(42, '3 bedrooms', '3 bedrooms', '3-bed-room-flats-and-houses-to-sale-offer', 1, 9, 3),".
            "(43, '4 bedrooms', '4 bedrooms', '4-bed-room-houses-to-sale-offer', 1, 9, 3),".
            "(44, '5 bedrooms', '5 bedrooms', '5-bed-room-houses-to-sale-offer', 1, 9, 3),".
            "(45, '5+ bedrooms', '5 or more bedrooms', 'more-than-5-bed-room-houses-to-sale-offer', 1, 9, 3)");

        $this->addSql(
            "INSERT INTO `sub_category` (`id`, `name`, `display`, `slug`, `category_id`, `parent_id`,`depth`) VALUES" .
            "(46, 'Studio Flat', 'Studio Flat', 'studio-flats-to-sale-wanted', 1, 10, 3),".
            "(47, '1 bedroom', '1 bedroom', '1-bed-room-flat-and-houses-to-sale-wanted', 1, 10, 3),".
            "(48, '2 bedrooms', '2 bedrooms', '2-bed-room-flats-and-houses-to-sale-wanted', 1, 10, 3),".
            "(49, '3 bedrooms', '3 bedrooms', '3-bed-room-flats-and-houses-to-sale-wanted', 1, 10, 3),".
            "(50, '4 bedrooms', '4 bedrooms', '4-bed-room-houses-to-sale-wanted', 1, 10, 3),".
            "(51, '5 bedrooms', '5 bedrooms', '1-bed-room-houses-to-sale-wanted', 1, 10, 3),".
            "(52, '5+ bedrooms', '5 or more bedrooms', 'more-than-5-bed-room-flats-and-houses-to-sale-wanted', 1, 10, 3)");
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

        //$this->addSql('TRUNCATE TABLE `sub_category`');
        $this->addSql('DELETE FROM sub_category WHERE depth = 3');
        $this->addSql('DELETE FROM sub_category WHERE depth = 2');
        $this->addSql('DELETE FROM sub_category WHERE depth = 1');
        //$this->addSql('TRUNCATE TABLE `category`');
    }
}
