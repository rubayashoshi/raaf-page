<?php

namespace HRPROJECT\FileManagerBundle\Service\Storage;

use HRPROJECT\FileManagerBundle\Entity\Document;

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