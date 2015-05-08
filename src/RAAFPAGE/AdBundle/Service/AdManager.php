<?php

namespace RAAFPAGE\AdBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use RAAFPAGE\AdBundle\Entity\Property;
use RAAFPAGE\UserBundle\Entity\User;

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

    public function __constructor(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllAdsByUser(User $user, ObjectManager $objectManager)
    {
        return $objectManager->getRepository('RAAFPAGEAdBundle:Property')->findBy(
            array('user' => $user)
        );
    }

    public function getPropertyById($id, ObjectManager $objectManager)
    {
        return $objectManager->getRepository('RAAFPAGEAdBundle:Property')->find($id);
    }

    public function addImageToProperty(property $property, ObjectManager $objectManager, FileUploader $fileUploader, $fileName)
    {
        $fileUploader->attacheImageIntoProperty($property, $fileName);
        $objectManager->persist($property);
        $objectManager->flush();
    }
}
