<?php

namespace RAAFPAGE\AdBundle\Service;

use RAAFPAGE\AdBundle\Entity\Image;
use RAAFPAGE\UserBundle\Entity\User;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class AdManager
 * @package RAAFPAGE\AdBundle\Service
 */
class AdManager
{
    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    /** @var  FileUploader */
    private $fileUploader;

    public function __construct(EntityManager $entityManager, FileUploader $fileUploader)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
    }

    private function getRepository()
    {
        return $this->entityManager->getRepository('RAAFPAGEAdBundle:Property');
    }

    public function getAllAdsByUser(User $user)
    {
        return $this->getRepository()->findBy(array('user' => $user));
    }

    public function getFindOneBy($propertyId, $name)
    {
        return $this->entityManager->getRepository('RAAFPAGEAdBundle:Image')->
            findOneBy(array('property' => $this->getPropertyById($propertyId), 'name' => $name));
    }

    public function getPropertyById($id)
    {
        return $this->getRepository()->find($id);
    }

    public function addImageToProperty($propertyId, $fileName)
    {
        $property = $this->getPropertyById($propertyId);

        $this->fileUploader->attacheImageIntoProperty($property, $fileName);
        $this->entityManager->persist($property);
        $this->entityManager->flush();
    }

    /**
     * @param string $imageId
     * @param int $propertyId
     */
    public function removeImageFromExistingProperty($imageId, $propertyId = null)
    {
        $originalImageName = str_replace(FileImageInfo::$thumb_prefix, '', $imageId);

        if ($propertyId) {
            //remove normal image from property
            $this->removeImageFromProperty($propertyId, $imageId);
            //remove thumbnail image from property
            $this->removeImageFromProperty($propertyId, $originalImageName);

            //remove from file system
            $this->fileUploader->removeImageForExistingProperty($originalImageName);
        } else {
            $this->fileUploader->removeImageForNewProperty($originalImageName);
        }
    }

    /**
     * @param int $propertyId
     * @param string $fileName
     */
    public function removeImageFromProperty($propertyId, $fileName)
    {
        $image = $this->getFindOneBy($propertyId, $fileName);

        if ($image instanceof Image) {
            $this->entityManager->remove($image);
            $this->entityManager->flush();
        }
    }
}
