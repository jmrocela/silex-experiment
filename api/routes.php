<?php
/**
 * we specify which controllers/methods are public, just for security
 */
return array(
	array(
		'name' => null, // named routes
		'pattern' => '/user/{param1}', // route
		'path' => 'Index/Index', // controller
		'method' => array('get', 'post'), 
		'converter' => null,
		'locale' => null, // defaults to site config
		'format' => 'json', // return format for the request, defaults to whatever is in http-accept
		// Implement this in the future
		'defaults' => array(),
		'asserts' => array(),
	),
	array(
		'name' => null, // named routes
		'pattern' => '/user/get', // route
		'path' => 'Index/Index', // controller
		'method' => array('get'), 
	)

);