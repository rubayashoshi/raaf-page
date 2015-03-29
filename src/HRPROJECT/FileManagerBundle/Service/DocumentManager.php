<?php

namespace HRPROJECT\FileManagerBundle\Service;

use Doctrine\ORM\EntityManager;
use HRPROJECT\FileManagerBundle\Entity\Document;
use Symfony\Bridge\Monolog\Logger;
use HRPROJECT\FileManagerBundle\LocalAssetStorage;

class DocumentManager
{
    /** @var string*/
    private $documentStoarage;

    /** @var EntityManager $entityManager */
    private $entityManager;

    /** @var Logger $logger */
    private $logger;

    /**
     * @param string $documentStorage
     * @param EntityManager $entityManager
     * @param Logger $logger
     */
    public function __construct($documentStorage, EntityManager $entityManager, Logger $logger)
    {
        $this->documentStoarage = $documentStorage;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function getDocumentList()
    {
        return $this->entityManager->getRepository('HRPROJECTFileManagerBundle:Document')
            ->findAll();
    }

    /**
     * @param Document $document
     */
    public function Upload(Document $document)
    {
        $assetStorage = StorageDecision::getStorage($this->documentStoarage);
        $document->setPath(StorageDecision::getPath($this->documentStoarage));
        $document->setName($document->getFile()->getClientOriginalName());
        $assetStorage->upload($document);
        $this->entityManager->persist($document);
        $this->entityManager->flush();
    }
}
