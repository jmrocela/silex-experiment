<?php
/**
 * (c) 2012 Springload. All rights reserved.
 */

/**
 * register our simple mongodb handler
 */
require_once FRAMEWORK_DIR . 'providers' . DS . 'MongoDBServiceProvider.php';
$app->register(new Solar\MongoDBServiceProvider(), array(
        'mongo.options' => array(
            'server' => DB_HOST,
            'dbname' => DB_NAME
        ),

        'mongo.common.class_path'  => FRAMEWORK_DIR . 'vendor' . DS . 'doctrine' . DS . 'common' . DS . 'lib',
        'mongo.mongodb.class_path'  => FRAMEWORK_DIR . 'vendor' . DS . 'doctrine' . DS . 'mongodb' . DS . 'lib',
        'mongo.mongodbodm.class_path'  => FRAMEWORK_DIR . 'vendor' . DS . 'doctrine' . DS . 'mongodb-odm' . DS . 'lib',

        'mongo.common.proxy_dir' => TEMP_DIR . 'cache',
        'mongo.common.hydrator_dir' => TEMP_DIR . 'cache',
        'mongo.common.documents_dir' => FRAMEWORK_DIR . 'core'
    ));

/**
 * register our session handler
 */
require_once FRAMEWORK_DIR . 'core' . DS . 'SessionHandler.php';
$app->register(new Silex\Provider\SessionServiceProvider());
$app['session.storage.handler'] = $app->share(function () use ($app) {
        return new Solar\SessionHandler($app['mongo'], array(
                'db_table' => 'sessions'
            ));
    });
$app['session']->start();

/**
 * register our logging service
 */
// 
$app->register(new Silex\Provider\MonologServiceProvider(), array(
        'monolog.name' => 'SPRINTPORT',
    	'monolog.logfile' => TEMP_DIR . 'logs' . DS . API_ENV_LEVEL . '.log'
    ));

/**
 * register our mustache provider
 */
require_once FRAMEWORK_DIR . 'providers' . DS . 'MustacheServiceProvider.php';
$app->register(new Solar\MustacheServiceProvider(), array(
        'mustache.path' => TEMPLATE_DIR
    ));

/**
 * register our security 
 *
 * @todo
 */
// Register the OpenAuth service provider.
require_once FRAMEWORK_DIR . 'providers' . DS . 'OPAuthServiceProvider.php';
require_once FRAMEWORK_DIR . 'providers' . DS . 'UserProvider.php';
require_once FRAMEWORK_DIR . 'providers' . DS . 'OPAuth' . DS . 'OPAuthAuthenticationProvider.php';
require_once FRAMEWORK_DIR . 'providers' . DS . 'OPAuth' . DS . 'OPAuthAuthenticationListener.php';
$app->register(new Solar\OPAuthServiceProvider(), array(
    'opauth.path' => '/auth',
    'opauth.config' => array(
        'callback_url' => '/auth/callback',
        'callback_transport' => 'session',
        'security_salt' => SECURITY_SALT,
        'Strategy' => array(
            'Facebook' => array(
                'app_id' => FACEBOOK_APP_ID,
                'app_secret' => FACEBOOK_APP_SECRET,
                'redirect_uri' => 'http://' . WWW_HOST . '/auth/facebook/int_callback'
            )
        )
    )
));

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secure' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/auth/check'
            ),
            'opauth.facebook' => array(
                'oauth_provider' => 'facebook',
                'login_path' => '/auth/facebook',
                'check_path' => '/auth/callback',
                'failure_path' => '/login?try_again'
            ),
            'logout' => array(
                'logout_path' => '/logout'
            ),
            'users' => $app->share(function () use ($app) {
                    return new Solar\UserProvider();
                })
        )
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN', 'https'),
        array('^/albums', 'ROLE_USER')
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'),
    )
));