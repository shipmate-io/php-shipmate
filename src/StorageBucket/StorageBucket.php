<?php

namespace Shipmate\Shipmate\StorageBucket;

use League\Flysystem\Filesystem;
use League\Flysystem\Visibility;

class StorageBucket extends Filesystem
{
    public function __construct(
        string $bucketName,
        string $visibility = Visibility::PRIVATE,
    ) {
        parent::__construct(
            adapter: new StorageBucketFilesystemAdapter(
                bucketName: $bucketName,
                visibility: $visibility
            )
        );
    }
}
