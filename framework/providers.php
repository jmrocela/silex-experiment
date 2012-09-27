<?php
/**
 * Providers
 *
 * this file is responsible with registering classes to the system. this also
 * supports autoloading that complies to PSR-0
 *
 * (c) 2012 Wanderlust. All rights reserved.
 */

/**
 * Register custom providers and classes
 */
$loader = \ComposerAutoloaderInit::getLoader();
$loader->add('Solar', FRAMEWORK_DIR);
$loader->add('Solar\\Providers', API_DIR);
$loader->add('Solar\\Controllers', API_DIR);

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

        'mongo.common.proxy_dir' => API_DIR . 'Solar' . DS . 'Documents' . DS . 'Proxy',
        'mongo.common.hydrator_dir' => API_DIR . 'Solar' . DS . 'Documents' . DS . 'Hydrator',
        'mongo.common.documents_dir' => API_DIR . 'Solar'
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
        'mustache.template_dir' => TEMPLATE_DIR,
        'mustache.cache_dir' => TEMP_DIR . 'cache',
        'mustache.extension' => '.ms'
    ));

/**
 * register the OpenAuth service provider.
 */
$app->register(new Solar\Providers\OPAuthServiceProvider(), array(
        'opauth.path' => '/secure/auth',
        'opauth.config' => array(
            'callback_url' => '/secure/auth/callback',
            'callback_transport' => 'session',
            'security_salt' => SECURITY_SALT,
            'Strategy' => array(
                'Facebook' => array(
                        'app_id' => FACEBOOK_APP_ID,
                        'app_secret' => FACEBOOK_APP_SECRET,
                        'redirect_uri' => 'http://' . WWW_HOST . '/secure/facebook/login',
                        'scope' => 'user_about_me, user_checkins, user_hometown, user_interests, user_likes, user_location, friends_about_me, friends_checkins, friends_hometown, friends_interests, friends_likes, friends_location'
                )
            )
        )
    ));

/**
 * register the Security service provider.
 */
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
        'security.firewalls' => array(
			'secure' => array(
				'pattern' => '^/',
				'anonymous' => true
			)
		),
        'security.access_rules' => array(),
        'security.role_hierarchy' => array()
    ));