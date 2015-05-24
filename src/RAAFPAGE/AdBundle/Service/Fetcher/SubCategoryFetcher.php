<?php
namespace RAAFPAGE\AdBundle\Service\Fetcher;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use RAAFPAGE\AdBundle\Entity\SubCategory;
use RAAFPAGE\SiteBundle\Service\Fetcher\Fetcher;

class SubCategoryFetcher extends Fetcher
{
    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->entityManager->getRepository('RAAFPAGEAdBundle:SubCategory');
    }

    /**
     * @param int $id
     *
     * @return SubCategory
     * @throws EntityNotFoundException
     */
    public function getById($id)
    {
        $subCategory = $this->getRepository()->find($id);

        return $this->returnObjectOrThrowException($subCategory, $id);
    }
}