<?php

namespace Solar;
 
// use some symfony stuff
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller {

	private $app;
	
	private $requestFormat = 'application/json';
	
	private $locale = 'en-US';
	
	private $session = null;
	
	private $log = null;
	
	private $mongo = null;
	
	private $mustache = null;

	public function __construct(Application $app)
	{
		$this->app = $app;
	
        $this->requestFormat = $app['request_format'];
        $this->locale = $app['locale'];
		$this->session = $app['session'];
		$this->security = $app['security'];
		$this->log = $app['monolog'];
		$this->mongo = $app['mongo'];
		$this->view = $app['view'];
		$this->firewalls = $app['security.firewalls'];
		
		// conditions
		$this->isLazy = ($app['lazy_load'] == 1) ? true: false;
		
		// template globals
		$this->template_globals = array(
					'test' => 1,
					'is_lazy' => $this->isLazy,
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
		$rendered = $view('layouts' . DS . $layout, $template_vars);
		
		// if widgets are available, we render it as well.
		if (isset($response['widgets'])) {
			$widgets = array_reverse((array) $response['widgets']); // i have no idea why it parses in reverse.
			foreach ($widgets as $key => $widget) {
				if ($this->isLazy) {
					$rendered->nest($key, "<script type=\"text/javascript\" data-template=\"$widget\" class=\"widget-load-later lazy-load\">templates.push('widgets" . DS . $widget . "')</script>");
				} else {
					$rendered->nest($key, $view('widgets' . DS . $widget));
				}
			}
		}
		
		// render the body
		return $rendered->nest('body', $view($template));
	}

}