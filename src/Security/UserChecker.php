<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user):void

    {




    }

    public function checkPostAuth(UserInterface $user):void
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_STANDBY', $roles)) {
            throw new CustomUserMessageAccountStatusException('Your user account no longer exists.');
        }

        }

}