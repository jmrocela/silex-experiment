<?php
/**
 * we specify which controllers/methods are public, just for security
 *
 * a few notes;
 * - trailing slashes are important. add if a pattern overlaps another. (should be flexible but hmmm)
 * - make sure the URL reads semantically
 */
return array(
	WWW_HOST => array(
		'prefix' => WWW_HOST_PATH,
		'routes' => array(
				array('pattern' => '/', 'path' => 'Frontend/index'),
				array('pattern' => '/auth', 'path' => 'User/auth')
			)
	),

	API_HOST => array(
		'prefix' => API_HOST_PATH,
		'routes' => array(
				array(
					'name' => null, // named routes
					'pattern' => '/', // route
					'path' => 'Index/Index', // controller
					'method' => null, 
					'accept' => 'json', // return format for the request, defaults to whatever is in http-accept
					'locale' => null, // defaults to site config
					'template' => null, // defaults to the template under the same namespace
					// Implement this in the future
					'defaults' => array(),
					'converter' => null,
					'asserts' => array()
				)
			)
	)
);