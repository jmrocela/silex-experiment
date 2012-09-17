<?php
/**
 * (c) 2012 Springload. All rights reserved.
 */
 
// use some symfony stuff
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
// require some of our files
require_once FRAMEWORK_DIR . 'core' . DS . 'Controller.php';

// register silex modules
require_once FRAMEWORK_DIR . 'providers.php';

// use our routes file
$routes = require_once API_DIR . 'routes.php';

// get factory for v1 of the api
$context = $app['controllers_factory'];

// apply our routes
foreach ($routes as $site => $handles) {

	// just for the current requesting server
	if ($_SERVER['SERVER_NAME'] == $site) {

		foreach ($handles['routes'] as $route) {

			// check if the 3 required fields are there. else, let's throw an error
			$route['method'] = (isset($route['method'])) ? $route['method']: array(GET, POST);

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
		    $context->match($route['pattern'], function($param1 = null, $param2 = null, $param3 = null) use ($app, $route) {
				if (method_exists($app->handle, $app->method)) {
					// prepare the method name
					$method = $app->method;
					
					// i have to find a way how they can assign fixed variables into the routes. strange.
					// but since REST methods shouldn't have more than 2 trailing mtehod identifiers, this is fine. for now.
					$args = func_get_args();
					
					// call the method from our instance
					$response = call_user_func_array(array($app->handle, $method), $args);
					$response = (is_string($response)) ? $response: json_encode($response);
					return new Response($response);
				} else {
					// method doesn't exist
					return Error::response('Developer Notice: Controller method "' . $app->method . '" does not exist.', 500);
				}
		    })
		    ->before(function(Request $request) use ($app, $route) {
				// prepare the names and method
				$controller = explode('/', $route['path']);
				$route_handle = $controller[0] . 'Controller';
				$app->method = $controller[1];
				
				// I have the power!
				$app->handle = new $route_handle();
				
				// execute the before handler if there is
				$request = $app->handle->before($request);

				// get the request accept header
				$app->handle->accept = (isset($route['accept'])) ? $route['accept']: $request->headers->get('Accept');
				$app->handle->locale = (isset($route['locale'])) ? $route['locale']: null;
				
				// attach some traits
				$app->handle->request = $request;
				$app->handle->apply($app);
		    })
		    ->after(function(Request $request, Response $response) use ($app, $route) {	
		    	// get the accepted content type
				$content_type = ($app->handle->accept) ? $app->handle->accept: DEFAULT_CONTENT_TYPE;

				// execute the after handler if there is
				$response = $app->handle->after($request, $response);

		    	// where are our templates located?
		    	if ($content_type == DEFAULT_CONTENT_TYPE) {
			    	$template = (!empty($route['template'])) ? $route['template']: str_replace('controller', '', strtolower(get_class($app->handle))) . DS . strtolower($app->method) . '.ms';
					if (file_exists(TEMPLATE_DIR . $template)) {
						$template = require_once TEMPLATE_DIR . $template;
					} else {
						throw new \Exception('Template "' . $template . '" could not be found. Make sure the template exists under ' . TEMPLATE_DIR);
					}
			    	// generate the rendered template
					$rendered = $app->handle->render($template, $response);

					// return the response
					return new Response($rendered, 200,  array('content-type' => $content_type));
				} else {
					// return the response
					return new Response($response->getContent(), 200,  array('content-type' => $content_type));
				}

		    })
		    ->convert($convert_name, function($param) use ($app, $route) {
				// convert the parameter if the converter is available
				if (isset($route['converter'])) {
					if ($callback = $route['converter']['callback']) {
						$param = call_user_func_array($callback, array($param));
					} else {
						$param = $app->handle->convert($param);
					}
				}
				return $param;
		    })
			->method($method);
		} 
		
		// mount on me baby
		$app->mount($handles['prefix'], $context);
	}
}

// error handler
$app->error(function (\Exception $e, $code) use($app) {

	if (API_DEBUG) {
		$app['monolog']->addDebug($e);
	}

	// see if we are in
	if ($_SERVER['SERVER_NAME'] == API_HOST) {
		
		// just for debugging purposes
		$debug = null;
		if (API_DEBUG) {
			$debug = "\n\n" . $e;
		}

		// switch through error codes
    	switch ($code) {
			case 500:
				return new Response(json_encode(array('type' => 'SprintGenericError', 'error' => 'There seems to be a problem with our Code. Don\'t worry, we are working on this right now.' . $debug, 'code' => $code)), $code, array('content-type' => 'application/json'));
			break;
	        case 405:
				return new Response(json_encode(array('type' => 'SprintGenericError', 'error' => 'Method is not allowed. Please refer to our Documenation at http://springload.com/api/docs/' . $debug, 'code' => $code)), $code, array('content-type' => 'application/json'));
			break;
	        case 404:
				return new Response(json_encode(array('type' => 'SprintGenericError', 'error' => 'You cannot access this URL directly. The namespace and action does not exist. Please refer to our Documenation at http://springload.com/api/docs/' . $debug, 'code' => $code)), $code, array('content-type' => 'application/json'));
			break;
	        case 403:
				return new Response(json_encode(array('type' => 'SprintGenericError', 'error' => 'You are unauthorized to perform this action. Please make sure your API key and API secret is valid.' . $debug, 'code' => $code)), $code, array('content-type' => 'application/json'));
			break;
	    }

	} else {
		$controller = new FrontendController();
				
		// execute the handlers
		$request = $controller->before(new Request());
		$controller->accept = $request->headers->get('Accept');
		$controller->apply($app);
		$response = new Response($controller->fourohfour());
		$response = $controller->after($request, $response);

		// load our 404 template. we don't need anything else actually
		$template = require_once TEMPLATE_DIR . '404.ms';
		$rendered = $controller->render($template, $response);
		return new Response($rendered, $code);

	}
});

// register our autoloader
spl_autoload_register(function($class_name) {

	if (strpos($class_name, 'Controller')) {
		require_once API_DIR . 'controllers' . DS . strtolower(str_replace('Controller', '', $class_name)) . '.php';
	}
	
});

// --- EOF