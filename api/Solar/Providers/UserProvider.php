<?php

namespace Solar\Providers;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {

        $user = $this->db->createQueryBuilder('\Documents\User')->field('username')->equals($username)->getQuery()->getSingleResult();

        if ($user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return new User($user->getUsername(), $user->getPassword(), array('ROLE_USER'), true, true, true, true);
    }

    public function loadUserByUserId($uid)
    {
        
        $user = $this->db->createQueryBuilder('\Documents\User')->field('user_id')->equals($uid)->getQuery()->getSingleResult();

        if ($user) {
            throw new UsernameNotFoundException(sprintf('User ID "%s" does not exist.', $uid));
        }

        return new User($user->getUsername(), $user->getPassword(), array('ROLE_USER'), true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}