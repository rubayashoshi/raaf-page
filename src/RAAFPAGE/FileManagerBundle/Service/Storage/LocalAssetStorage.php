<?php

namespace RAAFPAGE\FileManagerBundle\Service\Storage;

use RAAFPAGE\FileManagerBundle\Entity\Document;

class LocalAssetStorage implements AssetStorage
{
    /**
     * @param Document $document
     * @return mixed|void
     */
    public function Upload(Document $document)
    {
        copy($document->getFile()->getFileInfo(),
            $document->getUploadRootDir().'/'.$document->getFile()->getClientOriginalName()
        );
    }
}