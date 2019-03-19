<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SwitchToCustomerVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['ROLE_ALLOWED_TO_SWITCH'])
            && $subject instanceof UserInterface;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous or if the subject is not a user, do not grant access
        if (!$user instanceof UserInterface || !$subject instanceof UserInterface) {
            return false;
        }

        if (in_array('ROLE_CUSTOMER', $subject->getRoles())
            && $this->hasSwitchToCustomerRole($token)) {
            return true;
        }

        return false;
    }

    private function hasSwitchToCustomerRole(TokenInterface $token)
    {
        foreach ($token->getRoles() as $role) {
            if ($role->getRole() === 'ROLE_SWITCH_TO_CUSTOMER') {
                return true;
            }
        }

        return false;
    }
}
