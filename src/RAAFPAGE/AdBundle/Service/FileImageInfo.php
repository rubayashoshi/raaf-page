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
    public static $tempDestinationPath = '/home/foodity/www/raaf-page/web/uploads/temp/';
    public static $uploadDirectoryPath = '/home/foodity/www/raaf-page/web/uploads/property/';
    public $image_width;
    public static $imageName = '';

    public static function setImageName($userId, $imageName)
    {
        self::$imageName = $userId.'_'.$imageName;
    }

    public static function getImageFullName()
    {
        return self::$uploadDirectoryForTempImage.self::$thumb_prefix.self::$imageName;
    }

    public static function getThumbImageDestinationPath()
    {
        return self::$tempDestinationPath.self::$thumb_prefix.self::$imageName;
    }

    public static function getNormalImageDestinationPath()
    {
        return self::$uploadDirectoryForTempImage.self::$imageName;
    }
}