<?php

namespace Solar\OPAuth;

use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Solar\OPAuth\OPAuthAuthenticationProvider;
use Solar\OPAuth\OPAuthToken;

/**
 * @brief       Authentication listener handling OAuth Authentication requests.
 * @author      Gigablah <gigablah@vgmdb.net>
 */
class OPAuthAuthenticationListener extends AbstractAuthenticationListener
{
    private $oauthProvider;
    protected $httpUtils;

    /**
     * {@inheritdoc}
     */
    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, SessionAuthenticationStrategyInterface $sessionStrategy, HttpUtils $httpUtils, $providerKey, AuthenticationSuccessHandlerInterface $successHandler = null, AuthenticationFailureHandlerInterface $failureHandler = null, array $options = array(), LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null, \OPAuth $oauthProvider)
    {
        parent::__construct($securityContext, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, $options, $logger, $dispatcher);
        $this->oauthProvider = $oauthProvider;
        $this->httpUtils     = $httpUtils;
    }

    /**
     * {@inheritDoc}
     */
    protected function requiresAuthentication(Request $request)
    {
        if ($this->httpUtils->checkRequestPath($request, $this->options['check_path']) ||
            $this->httpUtils->checkRequestPath($request, $this->options['login_path'])
        ) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function attemptAuthentication(Request $request)
    {
        $opauth = $this->oauthProvider;
        // redirect to auth provider
        if ($this->httpUtils->checkRequestPath($request, $this->options['login_path'])) {
            return $opauth->run();
        }

        $response = null;
        switch($opauth->env['callback_transport']) {
            case 'session':
                $response = $_SESSION['opauth'];
                unset($_SESSION['opauth']);
                break;
            case 'post':
            case 'get':
                $response = unserialize(base64_decode($request->request->get('opauth')));
                break;
            default:
                throw new \LogicException(sprintf('The "%s" callback transport is not supported.', $opauth->env['callback_transport']));
                break;
        }

        if (is_null($response)) {
            throw new AuthenticationException('Authentication failed');
        }

        $username = $response['auth']['info']['name'];

        $authToken = new OPAuthToken(array());
        $authToken->setUser($username);
        $authToken->uid = $response['auth']['uid'];
        $authToken->provider = $response['auth']['provider'];

        return $this->authenticationManager->authenticate($authToken);
    }
}