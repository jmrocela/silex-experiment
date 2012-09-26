<?php

namespace Solar\Providers\OPAuth;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * @brief       Token for OAuth Authentication requests.
 * @author      Gigablah <gigablah@vgmdb.net>
 */
class OPAuthToken extends AbstractToken
{
    public $uid;
    public $provider;

    public function __construct($roles = array())
    {
        parent::__construct($roles);
        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}