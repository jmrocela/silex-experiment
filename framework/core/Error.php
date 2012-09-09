<?php

use Symfony\Component\HttpFoundation\Response;

class Error {

    public function __construct()
    {
	
    }
    
    public static function response($error = null, $code = 400, $type = 'SpringServerError')
    {
		// Add log
	
		// Return a Response object
		return new Response(json_encode(array('type' => $type, 'error' => $error, 'code' => $code)), $code, array('content-type' => 'application/json'));
    }

}