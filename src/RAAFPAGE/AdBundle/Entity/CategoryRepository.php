<?php

namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findAllCategories()
    {
        $result = $this->getEntityManager()
            ->createQuery("SELECT c FROM RAAFPAGEAdBundle:Category c ORDER BY c.displayPriority ASC")
            ->getResult();

        return $result;
    }
}
