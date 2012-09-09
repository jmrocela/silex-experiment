<?php
/**
 * (c) 2012 Springload. All rights reserved.
 */
 
// Do some path definitions
define('DS', DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', dirname(__FILE__) . DS);
define('ROOT_DIR', dirname(PUBLIC_DIR) . DS);
define('TEMP_DIR', ROOT_DIR . 'tmp' . DS);
define('FRAMEWORK_DIR', ROOT_DIR . 'framework' . DS);
define('API_DIR', ROOT_DIR . 'api' . DS);

// Require the Confiugration and Constants
require_once FRAMEWORK_DIR . 'config.php';
require_once FRAMEWORK_DIR . 'constants.php';

// Require the Silex Library
require_once FRAMEWORK_DIR . DS . 'vendor' . DS . 'autoload.php';

// Make some magic
$api = new Silex\Application();
$api['security.firewalls'] = array();

// Require the bootstrapper
require_once FRAMEWORK_DIR . 'application.php';

// Mount v1 API to the /v1 namespace
$api->mount('/1', $v1);

// Run it
$api->run();
// --- EOF