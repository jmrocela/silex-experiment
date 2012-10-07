<?php
/**
 * Application
 * 
 * responsible for dispatching routes and catching errors.
 * 
 * (c) 2012 Wanderlust. All rights reserved.
 */
 
// use some symfony stuff
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// register silex modules
require_once FRAMEWORK_DIR . 'providers.php';

// use our routes file
$routes = require_once API_DIR . 'routes.php';

// get factory for v1 of the api
$context = $app['controllers_factory'];

// get the request accept header
$app['request_format'] = $app->share(function() {
	$request = Request::createFromGlobals();
	return $request->headers->get('Accept');
});

// get the lazy header
$app['lazy_load'] = $app->share(function() {
	$request = Request::createFromGlobals();
	return $request->headers->get('Lazy'); // change this to 1 if you need to test lazy loading
});

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

			// bind the route to a name so we can call it easily later
			$name = (isset($route['name'])) ? $route['name']: str_replace(array('/', '{', '}'), '_', strtolower($route['pattern']));
			
			// let's set the layout
			$route['layout'] = (isset($route['layout'])) ? $route['layout']: 'default';
		    
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

					// we have another set of returns just for the widgets
					$response = $app->handle->apply($response);
					
					$response = (is_string($response)) ? $response: json_encode($response);
					return new Response($response);
				} else {
					// method doesn't exist
					return new Response('Developer Notice: Controller method "' . $app->method . '" does not exist.', 500,  array('content-type' => 'application/json'));
				}
		    })
		    ->before(function(Request $request) use ($app, $route) {
				// prepare the names and method
				$controller = explode('/', $route['path']);
				$route_handle = 'Solar\\Controllers\\' . $controller[0] . 'Controller';
				$app->method = $controller[1];

				// I have the power!
				$app->handle = new $route_handle($app);
				
				// execute the before handler if there is
				$request = $app->handle->before($request);

				// get the request accept header
				$app['request_format'] = $app->share(function() use ($route, $request) {
					return (isset($route['accept'])) ? $route['accept']: $request->headers->get('Accept');
				});

				// get the lazy header
				$app['lazy_load'] = $app->share(function() use ($route, $request) {
					return (isset($route['lazy'])) ? $route['lazy']: $app['lazy_load'];
				});

				// set locale
				$app['locale'] = $app->share(function() use ($route) {
					return (isset($route['locale'])) ? $route['locale']: null;
				});
				
				// attach some traits
				$app->handle->request = $request;
		    })
		    ->after(function(Request $request, Response $response) use ($app, $route) {
		    	// catch errors
		    	if ($response->getStatusCode() == 500) return $response;

		    	// get the accepted content type
				$content_type = ($app['request_format']) ? $app['request_format']: DEFAULT_CONTENT_TYPE;

				// execute the after handler if there is
				$response = $app->handle->after($request, $response);

		    	// where are our templates located?
		    	if ($content_type == DEFAULT_CONTENT_TYPE) {
			    	$template = (!empty($route['template'])) ? $route['template']: str_replace('controller', '', strtolower(str_replace("Solar\\Controllers\\", "", get_class($app->handle)))) . DS . strtolower($app->method);
					
					// generate the rendered template
					$rendered = $app->handle->render($template, $response, $route['layout']);

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
		    ->bind($name)
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
				return new Response(json_encode(array('type' => 'SprintGenericError', 'error' => 'Method is not allowed. Please refer to our Documenation at http://wanderlust.com/api/docs/' . $debug, 'code' => $code)), $code, array('content-type' => 'application/json'));
			break;
	        case 404:
				return new Response(json_encode(array('type' => 'SprintGenericError', 'error' => 'You cannot access this URL directly. The namespace and action does not exist. Please refer to our Documenation at http://wanderlust.com/api/docs/' . $debug, 'code' => $code)), $code, array('content-type' => 'application/json'));
			break;
	        case 403:
				return new Response(json_encode(array('type' => 'SprintGenericError', 'error' => 'You are unauthorized to perform this action. Please make sure your API key and API secret is valid.' . $debug, 'code' => $code)), $code, array('content-type' => 'application/json'));
			break;
	    }

	} else {
		$controller = new Solar\Controllers\FrontendController($app);
				
		// execute the handlers
		$request = $controller->before(new Request());
		$controller->requestFormat = $request->headers->get('Accept');
		$controller->apply($app);
		$response = new Response($controller->error($code));
		$response = $controller->after($request, $response);

		// load our 404 template. we don't need anything else actually
		$rendered = $controller->render($code, $response);
		return new Response($rendered, $code);

	}
});
