<?php
/**
 * we specify which controllers/methods are public, just for security
 *
 * a few notes;
 * - trailing slashes are important. add if a pattern overlaps another. (should be flexible but hmmm)
 * - make sure the URL reads semantically
 */
return array(
	WWW_HOST_PATH => array(),
	API_HOST_PATH => array(
		array(
			'name' => null, // named routes
			'pattern' => '/', // route
			'path' => 'Index/Index', // controller
			'method' => array('get', 'post'), 
			'accept' => 'json', // return format for the request, defaults to whatever is in http-accept
			'locale' => null, // defaults to site config
			// Implement this in the future
			'defaults' => array(),
			'converter' => null,
			'asserts' => array()
		)
	)
);