<?php
namespace RAAFPAGE\AdBundle\Service\Fetcher;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use RAAFPAGE\AdBundle\Entity\Status;
use RAAFPAGE\SiteBundle\Service\Fetcher\Fetcher;

class StatusFetcher extends Fetcher
{
    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->entityManager->getRepository('RAAFPAGEAdBundle:Status');
    }

    /**
     * @param int $id
     *
     * @return Status
     * @throws EntityNotFoundException
     */
    public function getStatusById($id)
    {
        $asset = $this->getRepository()->find($id);

        return $this->returnObjectOrThrowException($asset, $id);
    }

    /**
     * @param string $name
     *
     * @return Status
     * @throws EntityNotFoundException
     */
    public function getStatusByName($name)
    {
        $asset = $this->getRepository()->findOneBy(array('name' => $name));

        return $this->returnObjectOrThrowException($asset, $name);
    }
}