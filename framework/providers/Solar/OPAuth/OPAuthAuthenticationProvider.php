<?php

namespace Solar\OPAuth;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Solar\OPAuth\OpauthToken;

/**
 * @brief       Authentication provider handling OAuth Authentication requests.
 * @author      Gigablah <gigablah@vgmdb.net>
 */
class OPAuthAuthenticationProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $userChecker;
    private $providerKey;
    private $encoderFactory;

    public function __construct(UserProviderInterface $userProvider, $providerKey)
    {
        $this->userProvider = $userProvider;
        $this->providerKey  = $providerKey;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUserId($token->uid);
        if ($user) {
            $authenticatedToken = new OPAuthToken($user->getRoles());
            $authenticatedToken->setAuthenticated(true);
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('OPAuth Authentication Failed.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof OPAuthToken;
    }
}