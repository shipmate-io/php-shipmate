<?php

namespace Shipmate\Shipmate\JobQueue;

use Exception;
use Google\Cloud\Tasks\V2\CloudTasksClient;
use Google\Cloud\Tasks\V2\HttpMethod;
use Google\Cloud\Tasks\V2\HttpRequest;
use Google\Cloud\Tasks\V2\OidcToken;
use Google\Cloud\Tasks\V2\Task;
use Google\Protobuf\Timestamp;
use Shipmate\Shipmate\JobQueue\Exceptions\UnableToParseJob;
use Shipmate\Shipmate\ShipmateConfig;

class JobQueue
{
    protected ShipmateConfig $shipmateConfig;

    protected CloudTasksClient $googleClient;

    public function __construct(
        protected string $name,
        protected string $workerUrl,
    ) {
        $this->shipmateConfig = new ShipmateConfig;

        $this->googleClient = new CloudTasksClient([
            'projectId' => $this->shipmateConfig->getEnvironmentId(),
            'keyFile' => $this->shipmateConfig->getAccessKey(),
        ]);
    }

    public static function parseJob(string $requestPayload): Job
    {
        try {
            return new Job(
                payload: base64_decode(json_decode($requestPayload, true))
            );
        } catch (Exception) {
            throw new UnableToParseJob;
        }
    }

    public function publishJob(Job $job, int $availableAt = null): void
    {
        $httpRequest = new HttpRequest;
        $httpRequest->setUrl($this->workerUrl);
        $httpRequest->setHttpMethod(HttpMethod::POST);
        $httpRequest->setBody(base64_encode(json_encode($job->payload)));

        $token = new OidcToken;
        $token->setServiceAccountEmail($this->shipmateConfig->getAccessId());
        $httpRequest->setOidcToken($token);

        $task = new Task;
        $task->setHttpRequest($httpRequest);

        if ($availableAt > time()) {
            $task->setScheduleTime(new Timestamp(['seconds' => $availableAt]));
        }

        $fullyQualifiedQueueName = $this->googleClient->queueName(
            project: $this->shipmateConfig->getEnvironmentId(),
            location: $this->shipmateConfig->getRegionId(),
            queue: $this->name,
        );

        $this->googleClient->createTask($fullyQualifiedQueueName, $task);
    }

    public function getGoogleClient(): CloudTasksClient
    {
        return $this->googleClient;
    }
}
