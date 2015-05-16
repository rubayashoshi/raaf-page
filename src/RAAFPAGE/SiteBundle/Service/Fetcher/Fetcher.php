<?php

namespace RAAFPAGE\SiteBundle\Service\Fetcher;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

abstract class Fetcher
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Object $object
     * @param mixed|null $identifier
     *
     * @throws EntityNotFoundException
     * @return Object
     */
    protected function returnObjectOrThrowException($object, $identifier = null)
    {
        if (!$object) {
            $msg = $identifier ? '%s %s not found' : '%s not found';

            $class = $this->getRepository()->getClassName();

            throw new EntityNotFoundException(sprintf($msg, $class, $identifier), $class, $identifier);
        }

        return $object;
    }
}
