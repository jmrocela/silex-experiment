<?php
/**
 * (c) 2012 Springload. All rights reserved.
 */
 
// use some symfony stuff
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
// require some of our files
require_once FRAMEWORK_DIR . 'core' . DS . 'Controller.php';
require_once FRAMEWORK_DIR . 'core' . DS . 'Error.php';

// Register needed modules
$api->register(new Silex\Provider\SessionServiceProvider());
$api->register(new Silex\Provider\SecurityServiceProvider());
$api->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/development.log',
));
$api->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));
$api->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ),
));

// use our routes file
$routes = require_once API_DIR . 'routes.php';

// get factory for v1 of the api
$v1 = $api['controllers_factory'];

// apply our routes
foreach ($routes as $route) {

    // apply method
    $method = (is_array($route['method'])) ? strtoupper(implode('|', $route['method'])): $route['method'];
	
	// in case we have a converter method
	$convert_name = (isset($route['converter']['name'])) ? $route['converter']['name']: null;
    
	/**
	 * THERE SHOULD BE A BETTER WAY OF DOING THIS RATHER
	 * THAN ATTACHING EACH EVENT TO A SINGLE CHAIN.
	 *
	 * @todo
	 */
	
    // match the routes
    $v1->match($route['pattern'], function($param1 = null, $param2 = null, $param3 = null) use ($api, $route) {
		if (method_exists($api->handle, $api->method)) {
			// prepare the method name
			$method = $api->method;
			
			// i have to find our how they can assign fixed variables into the routes. strange.
			// but since REST methods shouldn't have more than 2 trailing mtehod identifiers, this is fine. for now.
			$args = func_get_args();
			
			// call the method from our instance
			call_user_func_array(array($api->handle, $method), $args);
		} else {
			// method doesn't exist
			return Error::response('Developer Notice: Controller method "' . $api->method . '" does not exist.', 500);
		}
    })
    ->before(function(Request $request) use ($api, $route) {
		// prepare the names and method
		$controller = explode('/', $route['path']);
		$route_handle = $controller[0] . 'Controller';
		$api->method = $controller[1];
		
		// i have the power!
		$api->handle = new $route_handle();
		
		// attach the request to the parent controller
		$api->handle->request = $request;
		
		// execute the before handler if there is
		$api->handle->before();
    })
    ->after(function(Request $request, Response $response) use ($api, $route) {		
		// execute the after handler if there is
		$api->handle->after();
    })
    ->convert($convert_name, function($param) use ($api, $route) {
		// convert the parameter if the converter is available
		if (isset($route['converter'])) {
			if ($callback = $route['converter']['callback']) {
				$param = call_user_func_array($callback, array($param));
			} else {
				$param = $api->handle->convert($param);
			}
		}
		return $param;
    })
	->method($method);
	
	// mount on me baby
	$api->mount('/v1', $v1);
} 

// error handler
$api->error(function (\Exception $e, $code) {

	if (API_DEBUG) {
		echo $e;
	}

    switch ($code) {
		case 500:
			return Error::response('There seems to be a problem with our Code. Don\'t worry, someone will get fired for this.', $code);
		break;
        case 405:
			return Error::response('Method is not allowed. Please refer to our Documenation at http://springload.com/api/docs/', $code);
		break;
        case 404:
			return Error::response('You cannot access this URL directly. The namespace and action does not exist. Please refer to our Documenation at http://springload.com/api/docs/', $code);
		break;
        case 403:
			return Error::response('You are unauthorized to perform this action. Please make sure your API key and API secret is valid.', $code);
		break;
    }
});

// register our autoload
spl_autoload_register(function($class_name) {

	if (strpos($class_name, 'Controller')) {
		require_once API_DIR . 'controllers' . DS . strtolower(str_replace('Controller', '', $class_name)) . '.php';
	}
	
});

// --- EOF