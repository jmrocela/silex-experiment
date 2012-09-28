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
				// generic pages
				array('pattern' => '/', 'path' => 'Frontend/index', 'layout' => 'home'),
				array('pattern' => '/about/', 'path' => 'Frontend/about', 'layout' => 'generic'),
				array('pattern' => '/features/', 'path' => 'Frontend/features', 'layout' => 'generic'),
				array('pattern' => '/developer/', 'path' => 'Frontend/developer', 'layout' => 'developer'),
				array('pattern' => '/press/', 'path' => 'Frontend/press', 'layout' => 'press'),
				array('pattern' => '/privacy/', 'path' => 'Frontend/privacy', 'layout' => 'generic'),
				array('pattern' => '/terms/', 'path' => 'Frontend/terms', 'layout' => 'generic'),
				
				// secure pages
				array('pattern' => '/secure/signup', 'path' => 'User/signup', 'layout' => 'user'),
				array('pattern' => '/secure/login', 'path' => 'User/login', 'layout' => 'user'),
				array('pattern' => '/secure/activate', 'path' => 'User/activate', 'layout' => 'user'),
				array('pattern' => '/secure/reset', 'path' => 'User/reset', 'layout' => 'user'),
				array('pattern' => '/secure/logout', 'path' => 'User/logout', 'layout' => 'user'),
				
				// advanced search
				
				// geo profiles
				
				// user dashboard & settings
				
				// user profiles
				
				// itineraries
				
				// itinerary builder
				
				// links, photos, videos, stamps, and achievements single page
				
			)
	),

	API_HOST => array(
		'prefix' => API_HOST_PATH,
		'routes' => array(
				array(
					'name' => null, // named routes
					'pattern' => '/', // route
					'path' => 'Frontend/Index', // controller
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