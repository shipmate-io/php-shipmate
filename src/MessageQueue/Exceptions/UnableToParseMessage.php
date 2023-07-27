<?php

namespace Shipmate\Shipmate\MessageQueue\Exceptions;

use Shipmate\Shipmate\ShipmateException;

class UnableToParseMessage extends ShipmateException
{
    public function __construct()
    {
        parent::__construct('Shipmate was unable to parse the given message.', 422);
    }
}
