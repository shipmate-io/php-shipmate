<?php

namespace Shipmate\Shipmate\MessageQueue;

use Exception;
use Google\Cloud\PubSub\PubSubClient;
use Shipmate\Shipmate\MessageQueue\Exceptions\UnableToParseMessage;
use Shipmate\Shipmate\ShipmateConfig;

class MessageQueue
{
    protected PubSubClient $googleClient;

    public function __construct(
        protected string $name
    ) {
        $shipmateConfig = new ShipmateConfig;

        $this->googleClient = new PubSubClient([
            'projectId' => $shipmateConfig->getEnvironmentId(),
            'keyFile' => $shipmateConfig->getAccessKey(),
        ]);
    }

    public static function parseMessage(string $requestPayload): Message
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

    public function publishMessage(Message $message): void
    {
        $this->googleClient->topic($this->name)->publish([
            'data' => json_encode($message->payload),
            'attributes' => [
                'type' => $message->type,
            ],
        ]);
    }

    public function getGoogleClient(): PubSubClient
    {
        return $this->googleClient;
    }
}
