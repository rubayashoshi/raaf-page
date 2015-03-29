<?php

namespace RAAFPAGE\FileManagerBundle\Service\Storage;

use RAAFPAGE\FileManagerBundle\Entity\Document;

interface AssetStorage
{
    /**
     * @param Document $asset
     * @return mixed
     */
    public function Upload(Document $asset);
}