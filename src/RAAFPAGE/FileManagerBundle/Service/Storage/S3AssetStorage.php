<?php

namespace RAAFPAGE\FileManagerBundle\Service\Storage;

use RAAFPAGE\FileManagerBundle\Entity\Document;
use RAAFPAGE\FileManagerBundle\Service\Storage\AssetStorage;
use Aws\S3\S3Client;

class S3AssetStorage implements AssetStorage
{
    // Create an array of configuration options
    private $config = array(
        'key'    => '',
        'secret' => '',
    );

    /**
     * @param Document $document
     * @return mixed|void
     */
    public function Upload(Document $document)
    {
        $client = S3Client::factory($this->config);
//        copy($document->getFile()->getFileInfo(),
//            $document->getUploadRootDir() . '/' . $document->getFile()->getClientOriginalName());

        $response = $client->putObject(array(
            'Bucket' => 'hr-project',
            'Key' => $document->getFile()->getClientOriginalName(),
            'Body' => fopen($document->getUploadRootDir() . '/' . $document->getFile()->getClientOriginalName(), 'r')
        ));

        return $response;
    }
}
