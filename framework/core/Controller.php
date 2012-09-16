<?php

namespace Solar;
 
// use some symfony stuff
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller {

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
		$this->session = $app['session'];
		$this->log = $app['monolog'];
		$this->mongo = $app['mongo'];
		$this->mustache = $app['mustache'];
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
		if ($this->accept == DEFAULT_CONTENT_TYPE) {
			return $this->mustache->render($template, json_decode($response));
		} else {
			return $response;
		}
	}

}

// --- EOF