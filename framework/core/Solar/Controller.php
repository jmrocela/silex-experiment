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
	
	private $view = null;

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
		$this->log = $app['monolog'];
		$this->mongo = $app['mongo'];
		$this->view = $app['view'];

		/**
		 * @set template data
		 */
		$this->globals = array();
	}

	public function render($template, Response $response)
	{
		return $this->_render($template, $response);
	}

	protected function _render($template, Response $response)
	{	
		$response = $response->getContent();
		/**
		 * if lazy is on, just return the response as json or
		 * else, we do nothing and render the whole page as html.
		 */
		if ($this->requestFormat == DEFAULT_CONTENT_TYPE) {
			$response = array_merge((array) json_decode($response), $this->globals);
			$control = $this->view;
			return $control($template, $response);
		} else {
			return $response;
		}
	}

}

// --- EOF