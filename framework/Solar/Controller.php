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

	public function __construct(Application $app)
	{
        $this->requestFormat = $app['request_format'];
        $this->locale = $app['locale'];
		$this->session = $app['session'];
		$this->security = $app['security'];
		$this->log = $app['monolog'];
		$this->mongo = $app['mongo'];
		$this->view = $app['view'];
		$this->firewalls = $app['security.firewalls'];
		
		// conditions
		$this->isLazy = $app['lazy_load'];
		
		// template globals
		$this->template_globals = array(
					'test' => 1,
					'test2' => 2,
					'session' => array(),
					'user' => array()
				);
	}

	public function __destruct() {}

	public function before(Request $request)
	{
		return $request;
	}

	public function after(Request $request, Response $response)
	{
		return $response;
	}

	public function render($template, Response $response, $layout = 'default')
	{
		return $this->_render($template, $response, $layout);
	}
	
	protected function _render($template, Response $response, $layout = 'default')
	{	
		$response = (array) json_decode($response->getContent());
		
		// assign this shiz
		$view = $this->view;
		$template_vars = array_merge($this->template_globals, $response);
		
		// render the templates
		return $view('layouts' . DS . $layout, $template_vars)->nest('body', $view($template));
	}
	
	public function generateLazyHook($name)
	{
	
	}


}

// --- EOF