<?php
namespace RAAFPAGE\AdBundle\Service;

class FileImageInfo
{
    public static $_thumb_square_size = 100;
    public static $max_image_size 	  = 100;
    public static $jpeg_quality       = 90;
    public static $thumb_prefix		  = "thumb_";
    public static $uploadDirectoryForTempImage = 'uploads/temp/';
    public static $imageWebPath = 'uploads/property/';
    public static $tempDestinationPath = '/home/foodity/www/raaf-page-backup/web/uploads/temp/';
    public static $uploadDirectoryPath = '/home/foodity/www/raaf-page-backup/web/uploads/property/';
    public $image_width;
    public static $imageName = '';
    public static $propertyId;

    public static $maxNumberOfImages = 8;

    /**
     * @param int $propertyId
     */
    public static function setPropertyId($propertyId)
    {
        self::$propertyId = $propertyId;
    }

    /**
     * @param int $userId
     * @param string $imageName
     */
    public static function setImageName($userId, $imageName)
    {
        self::$imageName = $userId.'_'.$imageName;
    }

    /**
     * @return string
     */
    public static function getImageFullName()
    {
        if (self::$propertyId) {
            return self::$imageWebPath.self::$thumb_prefix.self::$imageName;
        } else {
            return self::$uploadDirectoryForTempImage.self::$thumb_prefix.self::$imageName;
        }
    }

    /**
     * @return string
     */
    public static function getThumbImageDestinationPath()
    {
        if (self::$propertyId) {
            $destinationFolder = str_replace('temp', 'property', self::$tempDestinationPath);
        } else {
            $destinationFolder = self::$tempDestinationPath;
        }

        return $destinationFolder.self::$thumb_prefix.self::$imageName;
    }

    /**
     * @return string
     */
    public static function getNormalImageDestinationPath()
    {
        if (self::$propertyId) {
            return self::$imageWebPath.self::$imageName;
        } else {
            return self::$uploadDirectoryForTempImage.self::$imageName;
        }
    }
}
