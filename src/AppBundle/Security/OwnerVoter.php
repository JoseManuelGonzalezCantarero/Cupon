<?php
namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class OwnerVoter implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return 'ROLE_EDITAR_OFERTA' == $attribute;
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        foreach($attributes as $attribute)
        {
            if(false === $this->supportsAttribute($attribute))
            {
                continue;
            }

            $user = $token->getUser();

            if($object->getTienda()->getId() === $user->getId())
            {
                return VoterInterface::ACCESS_GRANTED;
            }
            else
            {
                return VoterInterface::ACCESS_DENIED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;

    }
}