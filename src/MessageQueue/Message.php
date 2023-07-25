<?php

namespace Shipmate\Shipmate\MessageQueue;

class Message
{
    public function __construct(
        public string $type,
        public mixed $payload,
        public ?string $id = null,
    ) {
    }
}
