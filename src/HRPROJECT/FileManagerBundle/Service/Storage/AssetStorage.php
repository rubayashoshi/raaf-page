<?php

namespace HRPROJECT\FileManagerBundle\Service\Storage;

use HRPROJECT\FileManagerBundle\Entity\Document;

interface AssetStorage
{
    /**
     * @param Document $asset
     * @return mixed
     */
    public function Upload(Document $asset);
}