<?php

namespace Shipmate\Shipmate\MessageQueue;

use Exception;
use Google\Cloud\PubSub\PubSubClient;
use Shipmate\Shipmate\MessageQueue\Exceptions\UnableToParseMessage;
use Shipmate\Shipmate\ShipmateConfig;

class MessageQueue
{
    private PubSubClient $client;

    public function __construct(
        private string $name
    ) {
        $shipmateConfig = new ShipmateConfig;

        $this->client = new PubSubClient([
            'projectId' => $shipmateConfig->getEnvironmentId(),
            'keyFile' => $shipmateConfig->getAccessKey(),
        ]);
    }

    public function publishMessage(Message $message): void
    {
        $this->client->topic($this->name)->publish([
            'data' => json_encode($message->payload),
            'attributes' => [
                'type' => $message->type,
            ],
        ]);
    }

    public function parseMessage(string $requestPayload): Message
    {
        try {
            $message = json_decode($requestPayload, true);

            return new Message(
                type: $message['message']['attributes']['type'],
                payload: json_decode(base64_decode($message['message']['data']), true),
                id: $message['message']['messageId'],
            );
        } catch (Exception) {
            throw new UnableToParseMessage;
        }
    }

    public function getGoogleClient(): PubSubClient
    {
        return $this->client;
    }
}
