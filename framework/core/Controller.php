<?php

namespace Solar;
 
// use some symfony stuff
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller {

	public function __construct()
	{
	
	}

	public function __destruct()
	{
	
	}

	public function before(Request $request)
	{
		return $request;
	}

	public function after(Request $request, Response $response)
	{
		return $response;
	}

	public function render($template, Response $response)
	{
		return $this->_render($template, $response);
	}

	protected function _render($template, Response $response)
	{
		$rendered = $this->mustache->render($template, $response);	
		return $rendered;
	}

}

// --- EOF