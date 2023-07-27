<?php

namespace Shipmate\Shipmate\Support;

use Google\Auth\AccessToken;

class OpenId
{
    public function validateToken(string $token, string $audience): void
    {
        (new AccessToken)->verify(
            token: $token,
            options: [
                'audience' => $audience,
                'throwException' => true,
            ]
        );
    }
}
