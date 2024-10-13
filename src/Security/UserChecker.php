<?php

namespace Kibuzn\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Kibuzn\Entity\User;
use Kibuzn\Exception\UnverifiedAccountException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        // Only check for our User entity
        if (!$user instanceof User) {
            return;
        }

        // Check if the user is verified
        if ($user->getVerifiedAt() === null) {
            // Throw an exception to prevent login
            throw new UnverifiedAccountException(
                'Your account is not verified. Please verify your email to continue.'
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // You can implement other checks after authentication here if needed
    }
}
