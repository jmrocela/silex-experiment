<?php

namespace Solar;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint;
use Solar\OPAuth\OPAuthAuthenticationListener;
use Solar\OPAuth\OPAuthAuthenticationProvider;

/**
 * @brief       Opauth authentication library integration.
 * @author      Gigablah <gigablah@vgmdb.net>
 */
class OPAuthServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['opauth'] = $app->share(function () use ($app) {
            $config = $app['opauth.config'];
            $config['path'] = $app['opauth.path'] . '/';
            return new \Opauth($config, false);
        });

        // generate the authentication factories
        foreach (array('facebook', 'google', 'twitter') as $type) {
            $app['security.authentication_listener.factory.opauth.'.$type] = $app->protect(function($name, $options) use ($type, $app) {
                if (!isset($app['security.authentication_listener.'.$name.'.opauth.'.$type])) {
                    $app['security.authentication_listener.'.$name.'.opauth.'.$type] = $app['security.authentication_listener.opauth._proto']($name, $options);
                }

                if (!isset($app['security.authentication_provider.'.$name.'.opauth'])) {
                    $app['security.authentication_provider.'.$name.'.opauth'] = $app['security.authentication_provider.opauth._proto']($name);
                }
                return array(
                    'security.authentication_provider.'.$name.'.opauth',
                    'security.authentication_listener.'.$name.'.opauth.'.$type,
                    null,
                    'pre_auth'
                );
            });
        }

        $app['security.authentication_listener.opauth._proto'] = $app->protect(function ($providerKey, $options) use ($app) {
            return $app->share(function () use ($app, $providerKey, $options) {
                if (!isset($app['security.authentication.success_handler.'.$providerKey])) {
                    $app['security.authentication.success_handler.'.$providerKey] = $app['security.authentication.success_handler._proto']($providerKey, $options);
                }

                if (!isset($app['security.authentication.failure_handler.'.$providerKey])) {
                    $app['security.authentication.failure_handler.'.$providerKey] = $app['security.authentication.failure_handler._proto']($providerKey, $options);
                }
                return new OpauthAuthenticationListener(
                    $app['security'],
                    $app['security.authentication_manager'],
                    $app['security.session_strategy'],
                    $app['security.http_utils'],
                    $providerKey,
                    $app['security.authentication.success_handler.'.$providerKey],
                    $app['security.authentication.failure_handler.'.$providerKey],
                    $options,
                    $app['logger'],
                    $app['dispatcher'],
                    $app['opauth']
                );
            });
        });

        $app['security.authentication_provider.opauth._proto'] = $app->protect(function ($name) use ($app) {
            return $app->share(function () use ($app, $name) {
                return new OPAuthAuthenticationProvider(
                    $app['security.user_provider.secure'],
                    $name
                );
            });
        });
    }

    public function boot(Application $app)
    {
        // fake route which will be handled by auth listener
        $app->match('/auth/{strategy}', function() {});

        // this route must be unsecured
        $app->match('/login/{strategy}/{callback}', function ($strategy, $callback) use ($app) {
            $app['opauth']->run();
        });
    }
}