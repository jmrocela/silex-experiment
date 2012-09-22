<?php
/**
 * (c) 2012 Springload. All rights reserved.
 */

/**
 * Register custom providers and classes
 */
$loader = \ComposerAutoloaderInit::getLoader();
$loader->add('Solar', FRAMEWORK_DIR . 'core');
$loader->add('Solar\\Providers', FRAMEWORK_DIR . 'providers');

/**
 * register our simple mongodb handler
 */
$app->register(new Solar\Providers\MongoDBServiceProvider(), array(
        'mongo.options' => array(
            'server' => DB_HOST,
            'dbname' => DB_NAME
        ),

        'mongo.common.class_path'  => FRAMEWORK_DIR . 'vendor' . DS . 'doctrine' . DS . 'common' . DS . 'lib',
        'mongo.mongodb.class_path'  => FRAMEWORK_DIR . 'vendor' . DS . 'doctrine' . DS . 'mongodb' . DS . 'lib',
        'mongo.mongodbodm.class_path'  => FRAMEWORK_DIR . 'vendor' . DS . 'doctrine' . DS . 'mongodb-odm' . DS . 'lib',

        'mongo.common.proxy_dir' => FRAMEWORK_DIR . 'core' . DS . 'Documents' . DS . 'proxy',
        'mongo.common.hydrator_dir' => FRAMEWORK_DIR . 'core' . DS . 'Documents' . DS . 'hydrator',
        'mongo.common.documents_dir' => FRAMEWORK_DIR . 'core'
    ));

/**
 * register our session handler
 */
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
$app->register(new Solar\Providers\MustacheServiceProvider(), array(
        'mustache.cache_dir' => TEMP_DIR . 'cache'
    ));

/**
 * register our security 
 *
 * @todo
 */
// Register the OpenAuth service provider.
$app->register(new Solar\Providers\OPAuthServiceProvider(), array(
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
                    return new Solar\Providers\UserProvider();
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