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
	
	private $widgets = array();

	public function __construct(Application $app)
	{		
		// inject this context with required stuff from the app object
		$this->inject($this, $app);
		
		// conditions
		$this->isLazy = ($app['lazy_load'] == 1) ? true: false;
		
		// template globals
		$this->template_globals = array(
					'WWW_HOST' => WWW_HOST,
					'API_HOST' => API_HOST,
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
	
	public function apply($response)
	{
		if ($this->widgets) {
			return array_merge(array('widgets' => $this->widgets), $response);
		}
		return $response;
	}
	
	public function addWidget($id, Widget $widget)
	{
		if (!isset($this->widgets[$id])) {
			$widget = $this->inject($widget, $this->app);
			$widget->create();
			$this->widgets[$id] = ($this->isLazy) ? $widget->template: $widget->render();
		}
	}
	
	public function inject($target, $with)
	{
		// we store app somewhere for reference
		$target->app = $with;
		
        $target->requestFormat = $with['request_format'];
        $target->locale = $with['locale'];
		$target->session = $with['session'];
		$target->security = $with['security'];
		$target->log = $with['monolog'];
		$target->mongo = $with['mongo'];
		$target->view = $with['view'];
		return $target;
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
					$rendered->nest($key, "<script type=\"text/javascript\" data-template=\"$widget\" class=\"widget-load-later lazy-load\">templates.push('" . $widget . "')</script>");
				} else {
					$rendered->nest($key, $widget);
				}
			}
		}
		
		// render the body
		return $rendered->nest('body', $view($template));
	}

}