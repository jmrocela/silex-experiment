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
					'session' => array('test' => 1),
					'user' => array()
				);
	}

	public function render($template, Response $response, $layout = 'default')
	{
		return $this->_render($template, $response, $layout);
	}
	
	public function lazy(Response $response)
	{
		return $this->_lazy($response);
	}
	
	protected function _render($template, Response $response, $layout = 'default')
	{	
		$response = $response->getContent();
		
		$view = $this->view;
		$template_vars = array_merge($this->template_globals, (array) json_decode($response));
		
		return $view('layouts' . DS . $layout, $template_vars)
					->nest('body', $view($template));
	}
	
	public function _lazy(Response $response)
	{
		$view = $this->view;
		return $view('lazy');
	}


}

// --- EOF