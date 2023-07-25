<?php

namespace Shipmate\Shipmate\JobQueue;

class Job
{
    public function __construct(
        public mixed $payload,
    ) {
    }
}
