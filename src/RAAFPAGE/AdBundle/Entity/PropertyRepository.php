<?php

namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PropertyRepository extends EntityRepository
{
    public function findAllPropertiesBySubCategory($subcategory)
    {
        $result = $this->getEntityManager()
            ->createQuery("SELECT p FROM RAAFPAGEAdBundle:Property p WHERE p.subCategory = :subCategory")
            ->setParameter(':subCategory', $subcategory)
            ->getResult();

        return $result;
    }
}
