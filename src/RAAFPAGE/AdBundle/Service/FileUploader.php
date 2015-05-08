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

    public function __constructor(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #####  This function will proportionally resize image #####
    public function normalResizeImage($source, $destination, $image_type, $max_size, $image_width, $image_height, $quality){

        //return false if nothing to resize
        if($image_width <= 0 || $image_height <= 0) {
            return false;
        }

        //do not resize if image is smaller than max size
        if($image_width <= $max_size && $image_height <= $max_size){
            if($this->saveImage($source, $destination, $image_type, $quality)){
                return true;
            }
        }

        //Construct a proportional size of new image
        $image_scale  = min($max_size/$image_width, $max_size/$image_height);
        $new_width	  = ceil($image_scale * $image_width);
        $new_height	  = ceil($image_scale * $image_height);
        //Create a new true color image
        $new_canvas	  = imagecreatetruecolor( $new_width, $new_height );

        //Copy and resize part of an image with resampling
        if(imagecopyresampled($new_canvas, $source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)) {
            //save resized image
            $this->saveImage($new_canvas, $destination, $image_type, $quality);
        }

        return true;
    }

    ##### This function corps image to create exact square, no matter what its original size! ######
    public function cropImageSquare($source, $destination, $image_type, $square_size, $image_width, $image_height, $quality){
        //return false if nothing to resize
        if($image_width <= 0 || $image_height <= 0){
            return false;
        }

        if( $image_width > $image_height )
        {
            $y_offset = 0;
            $x_offset = ($image_width - $image_height) / 2;
            $s_size 	= $image_width - ($x_offset * 2);
        } else {
            $x_offset = 0;
            $y_offset = ($image_height - $image_width) / 2;
            $s_size = $image_height - ($y_offset * 2);
        }

        $new_canvas	= imagecreatetruecolor( $square_size, $square_size); //Create a new true color image

        //Copy and resize part of an image with resampling
        if(imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $square_size, $square_size, $s_size, $s_size)){
            $this->saveImage($new_canvas, $destination, $image_type, $quality);
        }

        return true;
    }

    ##### Saves image resource to file #####
    public function saveImage($source, $destination, $image_type, $quality)
    {
        //determine mime type
        switch(strtolower($image_type)){
            case 'image/png':
                //save png file
                imagepng($source, $destination); return true;
                break;
            case 'image/gif':
                //save gif file
                imagegif($source, $destination); return true;
                break;
            case 'image/jpeg': case 'image/pjpeg':
                //save jpeg file
                imagejpeg($source, $destination, $quality); return true;
            break;
            default: return false;
        }
    }

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

    private function getFileName($filename)
    {
        $parts = explode( "/", $filename );
        return $parts[count($parts) - 1];
    }

    public function removeImage($imageId)
    {
        foreach (glob("/home/foodity/www/raaf-page/web/uploads/temp/*{$imageId}*") as $filename) {
            unlink($filename);
        }
    }

    public function removeImageForExistingProperty($imageId)
    {
        foreach (glob("/home/foodity/www/raaf-page/web/uploads/property/*{$imageId}*") as $filename) {
            unlink($filename);
        }
    }

}
