<?php

namespace RAAFPAGE\AdBundle\Service\Fetcher;

use Doctrine\ORM\EntityRepository;

Interface FetcherInterface
{
    /**
     * @return EntityRepository
     */
    public function getRepository();
}