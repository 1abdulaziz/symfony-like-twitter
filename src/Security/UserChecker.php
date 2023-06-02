<?php

namespace App\Security;


use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user){

        if (NULL == $user->getBannedUntil()) {
            return;
        }

        $now = new \DateTime();

        if ($now < $user->getBannedUntil()) {
            throw new AccessDeniedException('This user is banned until ' . $user->getBannedUntil()->format('Y-m-d H:i:s'));
        }


    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user){

    }
}