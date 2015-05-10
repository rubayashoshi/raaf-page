<?php

namespace RAAFPAGE\AdBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use RAAFPAGE\AdBundle\Entity\Image;
use RAAFPAGE\AdBundle\Entity\Property;

/**
 * Class FileUploader
 * @package RAAFPAGE\AdBundle\Service
 */
class FileUploader
{
    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __constructor(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $source
     * @param $destination
     * @param $imageType
     * @param $maxSize
     * @param $imageWidth
     * @param $imageHeight
     * @param $quality
     * @return bool
     */
    public function normalResizeImage(
        $source,
        $destination,
        $imageType,
        $maxSize,
        $imageWidth,
        $imageHeight,
        $quality
    ) {
        //return false if nothing to resize
        if ($imageWidth <= 0 || $imageHeight <= 0) {
            return false;
        }

        //do not resize if image is smaller than max size
        if ($imageWidth <= $maxSize && $imageHeight <= $maxSize) {
            if ($this->saveImage($source, $destination, $imageType, $quality)) {
                return true;
            }
        }

        //Construct a proportional size of new image
        $imageScale  = min($maxSize/$imageWidth, $maxSize/$imageHeight);
        $newWidth	  = ceil($imageScale * $imageWidth);
        $newHeight	  = ceil($imageScale * $imageHeight);

        //Create a new true color image
        $newCanvas	  = imagecreatetruecolor( $newWidth, $newHeight );

        //Copy and resize part of an image with resampling
        if (imagecopyresampled($newCanvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight)) {
            $this->saveImage($newCanvas, $destination, $imageType, $quality);
        }

        return true;
    }

    /**
     * @param $source
     * @param $destination
     * @param $imageType
     * @param $squareSize
     * @param $imageWidth
     * @param $imageHeight
     * @param $quality
     * @return bool
     */
    public function cropImageSquare(
        $source,
        $destination,
        $imageType,
        $squareSize,
        $imageWidth,
        $imageHeight,
        $quality
    ) {
        //return false if nothing to resize
        if ($imageWidth <= 0 || $imageHeight <= 0) {
            return false;
        }

        if( $imageWidth > $imageHeight ) {
            $offsetY = 0;
            $offsetX = ($imageWidth - $imageHeight) / 2;
            $sizeS 	= $imageWidth - ($offsetX * 2);
        } else {
            $offsetX = 0;
            $offsetY = ($imageHeight - $imageWidth) / 2;
            $sizeS = $imageHeight - ($offsetY * 2);
        }

        //Create a new true color image
        $newCanvas	= imagecreatetruecolor( $squareSize, $squareSize);

        //Copy and resize part of an image with resampling
        if (imagecopyresampled($newCanvas, $source, 0, 0, $offsetX, $offsetY, $squareSize, $squareSize, $sizeS, $sizeS)) {
            $this->saveImage($newCanvas, $destination, $imageType, $quality);
        }

        return true;
    }

    /**
     * @param $source
     * @param $destination
     * @param $imageType
     * @param $quality
     * @return bool
     */
    public function saveImage($source, $destination, $imageType, $quality)
    {
        //determine mime type
        switch(strtolower($imageType)){
            case 'image/png':
                imagepng($source, $destination);
                return true;
                break;
            case 'image/gif':
                imagegif($source, $destination);
                return true;
                break;
            case 'image/jpeg': case 'image/pjpeg':
                imagejpeg($source, $destination, $quality);
                return true;
                break;
            default:
                return false;
        }
    }

    /**
     * @param Property $property
     * @param int $userId
     */
    public function moveImageTo(Property $property, $userId)
    {
        //todo if same image is already exists in the property, then delete it
        foreach (glob(FileImageInfo::$tempDestinationPath.$userId."_*") as $filename) {
            $this->attacheImageIntoProperty($property, $filename);
            copy($filename, str_replace('temp','property',$filename));
            unlink($filename);
        }

        //todo if same image is already exists in the property, then delete it
        foreach (glob(FileImageInfo::$tempDestinationPath.FileImageInfo::$thumb_prefix.$userId."_*") as $filename) {
            $this->attacheImageIntoProperty($property, $filename);
            copy($filename, str_replace('temp','property',$filename));
            unlink($filename);
        }
    }

    /**
     * @param $property
     * @param $fileName
     */
    public function attacheImageIntoProperty($property, $fileName)
    {
        $onlyFileName = $this->getFileName($fileName);
        $image = new Image();
        $image->setProperty($property);
        $image->setAddress(FileImageInfo::$imageWebPath.$onlyFileName);
        $image->setName($fileName);
        $property->addImage($image);
    }

    /**
     * @param Property $property
     * @param $userId
     */
    public function attacheImageToProperty(Property $property, $userId)
    {
        foreach (glob(FileImageInfo::$uploadDirectoryPath.$userId."_*") as $filename) {
            $onlyFileName = $this->getFileName($filename);

            if (!$property->hasImage($onlyFileName)) {
                $image = new Image();
                $image->setProperty($property);
                $image->setAddress(FileImageInfo::$imageWebPath.$onlyFileName);
                $image->setName($onlyFileName);
                $property->addImage($image);
            }
        }

        foreach (glob(FileImageInfo::$uploadDirectoryPath.FileImageInfo::$thumb_prefix.$userId."_*") as $filename) {
            $onlyFileName = $this->getFileName($filename);

            if (!$property->hasImage($onlyFileName)) {
                $image = new Image();
                $image->setProperty($property);
                $image->setAddress(FileImageInfo::$imageWebPath.$onlyFileName);
                $image->setName($onlyFileName);
                $property->addImage($image);
            }
        }
    }

    /**
     * @param string $filename
     * @return mixed
     */
    private function getFileName($filename)
    {
        $parts = explode( "/", $filename );

        return $parts[count($parts) - 1];
    }

    /**
     * @param int $imageId
     */
    public function removeImage($imageId)
    {
        foreach (glob(FileImageInfo::$tempDestinationPath."*{$imageId}*") as $filename) {
            unlink($filename);
        }
    }

    /**
     * @param int $imageId
     */
    public function removeImageForExistingProperty($imageId)
    {
        foreach (glob(FileImageInfo::$uploadDirectoryPath."*{$imageId}*") as $filename) {
            unlink($filename);
        }
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getImagesForNewAd($userId)
    {
        $images = array();

        for ($i = 0; $i < FileImageInfo::$maxNumberOfImages; $i++) {
            $images[$i] = 'missing';
        }

        $i = 0;

        foreach (glob(FileImageInfo::$uploadDirectoryForTempImage.'thumb_'.$userId."_*") as $filename) {
            $images[$i] = FileImageInfo::$uploadDirectoryForTempImage . $filename;
            $i++;
        }

        return $images;
    }

    /**
     * @param Property $property
     * @return array
     */
    public function getImages(Property $property)
    {
        $images = array();

        for ($i = 0; $i < FileImageInfo::$maxNumberOfImages; $i++) {
            $images[$i] = 'missing';
        }

        $i = 0;
        foreach ($property->getImages() as $image) {
            if (stripos($image->getAddress(), 'thumb')) {
                unset($images[$i]);
                $images[$i] =  $image->getAddress();
                $i++;
            }
        }

        return $images;
    }
}
