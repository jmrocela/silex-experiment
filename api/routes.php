<?php
/**
 * we specify which controllers/methods are public, just for security
 *
 * a few notes;
 * - trailing slashes are important. add if a pattern overlaps another. (should be flexible but hmmm)
 * - make sure the URL reads semantically
 */
return array(
	array(
		'name' => null, // named routes
		'pattern' => '/user/{param1}/get', // route
		'path' => 'Index/Index', // controller
		'method' => array('get', 'post'), 
		'converter' => null,
		'locale' => null, // defaults to site config
		'format' => 'json', // return format for the request, defaults to whatever is in http-accept
		// Implement this in the future
		'defaults' => array(),
		'asserts' => array(),
	)

);