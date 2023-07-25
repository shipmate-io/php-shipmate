<?php

namespace Shipmate\Shipmate\JobQueue\Exceptions;

use Shipmate\Shipmate\ShipmateException;

class UnableToParseJob extends ShipmateException
{
    public function __construct()
    {
        parent::__construct("Shipmate was unable to parse the given job.", 422);
    }
}
