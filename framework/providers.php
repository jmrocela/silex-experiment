<?php
/**
 * (c) 2012 Springload. All rights reserved.
 */

// register our session handler
$app->register(new Silex\Provider\SessionServiceProvider());

// register our security @todo
/*$app->register(new Silex\Provider\SecurityServiceProvider(), array(
	'security.firewalls' => array()
));*/

// register our logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => TEMP_DIR . 'logs' . DS . API_ENV_LEVEL . '.log',
	'monolog.name' => 'SPRINTPORT'
));

// register our mysql database
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'dbs.options' => array (
        'db_main_mysql' => array(
            'driver' => 'pdo_mysql',
            'host' => DB_HOST,
            'dbname' => DB_NAME,
            'user' => DB_USERNAME,
            'password' => DB_PASSWORD,
        )
    )
));

// register our mongodb database
require_once FRAMEWORK_DIR . 'providers' . DS . 'MongoDBServiceProvider.php';
$app->register(new MongoDBServiceProvider());

// register our mustache provider
require_once FRAMEWORK_DIR . 'providers' . DS . 'MustacheServiceProvider.php';
$app->register(new MustacheServiceProvider(), array(
        'mustache.path' => TEMPLATE_DIR
    ));