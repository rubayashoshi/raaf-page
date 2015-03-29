<?php

namespace HRPROJECT\FileManagerBundle\Service;

use HRPROJECT\FileManagerBundle\Service\Storage\LocalAssetStorage;
use HRPROJECT\FileManagerBundle\Service\Storage\S3AssetStorage;

final class StorageDecision
{
    public static $storageTypes = array(
        'local' => 'LocalAssetStorage',
        'aws'   => 'S3AssetStorage'
    );

    public static $allowedMimeTypes = array(
        'image/jpeg',
        'image/png',
        'image/gif'
    );

    public static $assetDestinationPath = array(
        'local' => 'web/assets',
        'aws'   => 'bucket'
    );

    public static $assetPath = array(
        'local' => 'uploads/documents',
        'aws'   => 'https://hr-project.s3.amazonaws.com'
    );

    public static function getStorage($type)
    {
        //return new self::$storageTypes[$type]();

        if ($type == 'local') {
            return new LocalAssetStorage();
        } else {
            return new S3AssetStorage();
        }
    }

    public static function getPath($type)
    {
        return self::$assetPath[$type];
    }
}
