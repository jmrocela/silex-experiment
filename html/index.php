<?php
/**
 * Index
 *
 * responsible for loading everything and running the app.
 *
 * (c) 2012 Wanderlust. All rights reserved.
 */
 
// Do some path definitions
define('DS', DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', dirname(__FILE__) . DS);
define('ROOT_DIR', dirname(PUBLIC_DIR) . DS);
define('TEMP_DIR', ROOT_DIR . 'tmp' . DS);
define('FRAMEWORK_DIR', ROOT_DIR . 'framework' . DS);
define('API_DIR', ROOT_DIR . 'api' . DS);
define('TEMPLATE_DIR', API_DIR . 'templates' . DS);

// Require the Confiugration and Constants
require_once FRAMEWORK_DIR . 'config.php';
require_once FRAMEWORK_DIR . 'constants.php';
$app['debug'] = API_DEBUG;

// Require the Silex Library
require_once FRAMEWORK_DIR . DS . 'vendor' . DS . 'autoload.php';

// Make some magic
$app = new Silex\Application();

// Require the bootstrapper
require_once FRAMEWORK_DIR . 'application.php';

// Run it
$app->run();
// --- EOF