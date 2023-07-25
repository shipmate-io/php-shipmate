<?php

namespace Shipmate\Shipmate;

class ShipmateConfig
{
    /**
     * The access ID used to authenticate with Shipmate.
     */
    public function getAccessId(): string
    {
        $accessId = getenv('SHIPMATE_ACCESS_ID');

        if (! $accessId) {
            throw new ShipmateException('The `SHIPMATE_ACCESS_ID` environment variable is not set.');
        }

        return $accessId;
    }

    /**
     * The access key used to authenticate with Shipmate.
     */
    public function getAccessKey(): array
    {
        $accessKey = getenv('SHIPMATE_ACCESS_KEY');

        if (! $accessKey) {
            throw new ShipmateException('The `SHIPMATE_ACCESS_KEY` environment variable is not set.');
        }

        $decodedAccessKey = json_decode(base64_decode($accessKey), true);

        if (! is_array($decodedAccessKey)) {
            throw new ShipmateException(
                'The `SHIPMATE_ACCESS_KEY` environment variable does not contain a valid access key.'
            );
        }

        return $decodedAccessKey;
    }

    /**
     * The id of the Shipmate environment.
     */
    public function getEnvironmentId(): string
    {
        $environmentId = getenv('SHIPMATE_ENVIRONMENT_ID');

        if (! $environmentId) {
            throw new ShipmateException('The `SHIPMATE_ENVIRONMENT_ID` environment variable is not set.');
        }

        return $environmentId;
    }

    /**
     * The id of the region in which the Shipmate environment is created.
     */
    public function getRegionId(): string
    {
        $regionId = getenv('SHIPMATE_REGION_ID');

        if (! $regionId) {
            throw new ShipmateException('The `SHIPMATE_REGION_ID` environment variable is not set.');
        }

        return $regionId;
    }
}
