<?php

namespace Solar;
 
// use some symfony stuff
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller {
	
	private $requestFormat = 'application/json';
	
	private $locale = 'en-US';
	
	private $session = null;
	
	private $log = null;
	
	private $mongo = null;
	
	private $mustache = null;

	public function __construct() {}

	public function __destruct() {}

	public function before(Request $request)
	{
		return $request;
	}

	public function after(Request $request, Response $response)
	{
		return $response;
	}

	public function apply(Application $app)
	{
        $this->requestFormat = $app['request_format'];
        $this->locale = $app['locale'];
		$this->session = $app['session'];
		$this->security = $app['security'];
		$this->log = $app['monolog'];
		$this->mongo = $app['mongo'];
		$this->view = $app['view'];
		$this->firewalls = $app['security.firewalls'];
		
		// template globals
		$this->template_globals = array(
					'session' => array(),
					'user' => array()
				);
	}

	public function render($template, Response $response, $layout = 'default')
	{
		return $this->_render($template, $response, $layout);
	}

	protected function _render($template, Response $response, $layout = 'default')
	{	
		$response = $response->getContent();
		/**
		 * if lazy is on, just return the response as json or
		 * else, we do nothing and render the whole page as html.
		 */
		$view = $this->view;
		return $view('layouts' . DS . $layout, $this->template_globals)->nest('body', $view($template, (array) json_decode($response)));
	}

}

// --- EOF