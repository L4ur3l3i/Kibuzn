<?php

namespace Kibuzn\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UnverifiedAccountException extends CustomUserMessageAuthenticationException
{
    public function __construct()
    {
        // Provide the custom message when this exception is thrown
        parent::__construct('Your account is not verified. Please verify your email to continue.');
    }
}
