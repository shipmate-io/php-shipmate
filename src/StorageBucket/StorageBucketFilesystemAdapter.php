<?php

namespace Shipmate\Shipmate\StorageBucket;

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;
use League\Flysystem\Visibility;
use Shipmate\Shipmate\ShipmateConfig;

class StorageBucketFilesystemAdapter extends GoogleCloudStorageAdapter
{
    protected StorageClient $googleClient;

    public function __construct(
        string $bucketName,
        string $visibility = Visibility::PRIVATE,
    ) {
        $shipmateConfig = new ShipmateConfig;

        $this->googleClient = new StorageClient([
            'keyFile' => $shipmateConfig->getAccessKey(),
            'projectId' => $shipmateConfig->getEnvironmentId(),
        ]);

        $bucket = $this->googleClient->bucket($bucketName);

        parent::__construct(
            bucket: $bucket,
            defaultVisibility: $visibility
        );
    }

    public function getGoogleClient(): StorageClient
    {
        return $this->googleClient;
    }
}
