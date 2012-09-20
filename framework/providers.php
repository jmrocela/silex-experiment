<?php
/**
 * (c) 2012 Springload. All rights reserved.
 */

/**
 * register our simple mongodb handler
 */
require_once FRAMEWORK_DIR . 'providers' . DS . 'MongoDBServiceProvider.php';
$app->register(new DRodin\Extension\MongoDBServiceProvider(), array(
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
$app->register(new MustacheServiceProvider(), array(
        'mustache.path' => TEMPLATE_DIR
    ));

/**
 * register our security 
 *
 * @todo
 */
/*require_once FRAMEWORK_DIR . 'providers' . DS . 'OPAuthServiceProvider.php';
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'default' => array(
            'opauth' => true
        )
    )
));*/