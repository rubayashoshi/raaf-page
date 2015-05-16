<?php

namespace RAAFPAGE\AdBundle\Service;

use Doctrine\ORM\EntityRepository;
use RAAFPAGE\AdBundle\Entity\Image;
use RAAFPAGE\AdBundle\Entity\Property;
use RAAFPAGE\AdBundle\Service\Fetcher\StatusFetcher;
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

    /**
     * @var StatusFetcher
     */
    private $statusFetcher;

    /**
     * @param EntityManager $entityManager
     * @param FileUploader $fileUploader
     * @param StatusFetcher $statusFetcher
     */
    public function __construct(EntityManager $entityManager, FileUploader $fileUploader, StatusFetcher $statusFetcher)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->statusFetcher = $statusFetcher;
    }

    /**
     * @return EntityRepository
     */
    private function getRepository()
    {
        return $this->entityManager->getRepository('RAAFPAGEAdBundle:Property');
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAllLiveAdsByUser(User $user)
    {
        return $this->getRepository()->findBy(array('user' => $user, 'status' => $this->statusFetcher->getStatusByName('live')));
    }

    /**
     * @param array $ads
     * @return array
     */
    public function getDefaultImages(array $ads)
    {
        //todo improve manage default image
        $images = array();

        /** @var Property $ad */
        foreach ($ads as $ad) {
            /** @var Image $image */
            foreach ($ad->getImages() as $image) {
                if (strpos($image->getAddress(), 'thumb') !==false) {
                    $images[$ad->getId()] = $image->getAddress();
                    continue;
                }
            }
        }

        return $images;
    }

    /**
     * @param int $propertyId
     * @param string $name
     * @return null|object
     */
    public function getFindOneBy($propertyId, $name)
    {
        return $this->entityManager->getRepository('RAAFPAGEAdBundle:Image')->
            findOneBy(array('property' => $this->getPropertyById($propertyId), 'name' => $name));
    }

    /**
     * @param Property $property
     */
    public function delete(Property $property)
    {
//        $adStatus = $this->entityManager->getRepository('RAAFPAGEAdBundle:Status')
//            ->findOneBy(array('name' => 'archived'));
        $adStatus = $this->statusFetcher->getStatusByName('archived');
        $property->setStatus($adStatus);

        $this->entityManager->persist($property);
        $this->entityManager->flush($property);
    }

    /**
     * @param int $id
     * @return object
     */
    public function getPropertyById($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param int $propertyId
     * @param string $fileName
     */
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
