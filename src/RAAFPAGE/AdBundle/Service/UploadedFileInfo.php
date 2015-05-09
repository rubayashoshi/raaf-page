<?php
namespace RAAFPAGE\AdBundle\Service;

class UploadedFileInfo
{
    public static $imageName;
    public static $imageSize;
    public static $imageTmpName;
    public static $imageWidth;
    public static $imageHeight;
    public static $imageType;
    public static $imageExtension;

    public static function populateImageInfo()
    {
        self::$imageName = $_FILES['image_file']['name'];
        self::$imageSize = $_FILES['image_file']['size'];
        self::$imageTmpName = $_FILES['image_file']['tmp_name'];
        $imageSizeInfo 	= getimagesize(self::$imageTmpName);

        if ($imageSizeInfo) {
            self::$imageWidth	= $imageSizeInfo[0];
            self::$imageHeight	= $imageSizeInfo[1];
            self::$imageType	= $imageSizeInfo['mime'];
        } else {
            die("Make sure image file is valid!");
        }

        $imageInfo = pathinfo(self::$imageName);
        self::$imageExtension = strtolower($imageInfo["extension"]);
    }
}