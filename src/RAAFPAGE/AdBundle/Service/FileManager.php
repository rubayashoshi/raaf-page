<?php

namespace RAAFPAGE\AdBundle\Service;

use Symfony\Component\Security\Core\SecurityContext;

class FileManager
{
    /** @var AdManager */
    private $adManager;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * @param AdManager $adManager
     * @param FileUploader $fileUploader
     * @param SecurityContext $securityContext
     */
    public function __construct(AdManager $adManager, FileUploader $fileUploader, SecurityContext $securityContext)
    {
        $this->adManager = $adManager;
        $this->fileUploader = $fileUploader;
        $this->securityContext = $securityContext;
    }

    /**
     * @return string
     */
    public function uploadAnImageAndReturnWebPath()
    {
        $filePathTemp = '';
        $user = $this->securityContext->getToken()->getUser();

        //store image information into UploadFileInfo class
        UploadedFileInfo::populateImageInfo();

        switch (UploadedFileInfo::$imageType) {
            case 'image/png':
                $imageRes =  imagecreatefrompng(UploadedFileInfo::$imageTmpName);
                break;
            case 'image/gif':
                $imageRes =  imagecreatefromgif(UploadedFileInfo::$imageTmpName);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                $imageRes = imagecreatefromjpeg(UploadedFileInfo::$imageTmpName);
                break;
            default:
                $imageRes = false;
        }

        if ($imageRes) {
            //for new image there will be no extension
            if (strpos($_POST['image_id'], UploadedFileInfo::$imageExtension) !== false) {
                $arr = explode('_',$_POST['image_id']);
                $newFileName = $arr[count($arr) - 2] . '.' . $arr[count($arr) - 1];
            } else {
                $newFileName = $_POST['image_id'] . '.' . UploadedFileInfo::$imageExtension;
            }

            /** if existing image removed and added a new one*/
            //todo update fron end to send always same image_id to avoid this work around
            if (strpos($newFileName, 'upload') !== false) {
                $parts = explode('/',$newFileName);
                $newFileName = $parts[count($parts) - 1];
            }

            FileImageInfo::setImageName($user->getId(), $newFileName);

            if(
                $this->fileUploader->normalResizeImage(
                    $imageRes,
                    FileImageInfo::getNormalImageDestinationPath(),
                    UploadedFileInfo::$imageType,
                    FileImageInfo::$max_image_size,
                    UploadedFileInfo::$imageWidth,
                    UploadedFileInfo::$imageHeight,
                    FileImageInfo::$jpeg_quality
                )
            ) {
                //call crop_image_square() function to create square thumbnails
                if (!$this->fileUploader->cropImageSquare(
                    $imageRes,
                    FileImageInfo::getThumbImageDestinationPath(),
                    UploadedFileInfo::$imageType,
                    FileImageInfo::$_thumb_square_size,
                    UploadedFileInfo::$imageWidth,
                    UploadedFileInfo::$imageHeight,
                    FileImageInfo::$jpeg_quality)
                ) {
                    die('Error Creating thumbnail');
                }

                $filePathTemp = FileImageInfo::getImageFullName();
            }

            //freeup memory
            imagedestroy($imageRes);
        }

        return $filePathTemp;
    }

    /**
     * @param int $propertyId
     * @param int $imageId
     */
    public function removeImage($propertyId, $imageId)
    {
        foreach (glob(FileImageInfo::$tempDestinationPath."*{$imageId}*") as $filename) {
            $this->adManager->removeImageFromProperty($propertyId, $filename);
            unlink($filename);
        }
    }

    public static function isImageExists($imageName)
    {
        return (file_exists(FileImageInfo::$imageBaseDir . $imageName)) ? true : false;
    }
}
