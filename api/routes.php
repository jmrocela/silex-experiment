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
				array('pattern' => '/company/', 'path' => 'Frontend/company', 'layout' => 'generic'),
				array('pattern' => '/brag/', 'path' => 'Frontend/brag', 'layout' => 'generic'),
				array('pattern' => '/developer/', 'path' => 'Frontend/developer', 'layout' => 'developer'),
				array('pattern' => '/help/', 'path' => 'Frontend/help', 'layout' => 'help'),
				array('pattern' => '/privacy/', 'path' => 'Frontend/privacy', 'layout' => 'generic'),
				array('pattern' => '/terms/', 'path' => 'Frontend/terms', 'layout' => 'generic'),
				//array('pattern' => '/careers/', 'path' => 'Frontend/careers', 'layout' => 'generic'),
				
				// secure pages
				array('pattern' => '/secure/signup/', 'secure' => true, 'path' => 'User/signup', 'layout' => 'secure', 'name' => 'register'),
				array('pattern' => '/secure/login/', 'secure' => true, 'path' => 'User/login', 'layout' => 'secure', 'name' => 'login'),
				array('pattern' => '/secure/activate/', 'secure' => true, 'path' => 'User/activate'),
				array('pattern' => '/secure/reset/', 'secure' => true, 'path' => 'User/reset', 'layout' => 'secure'),
				array('pattern' => '/secure/logout/', 'secure' => true, 'path' => 'User/logout', 'layout' => 'secure'),
				array('pattern' => '/secure/process/{where}', 'secure' => true, 'path' => 'User/process', 'layout' => 'new_user'),
				
				// advanced search
				array('pattern' => '/search/', 'path' => 'Search/index'),
				
				// user profiles
				array('pattern' => '/u/{profile_id}/', 'path' => 'Profile/user'),
				
				// user dashboard & settings
				
				// itineraries
				
				// itinerary builder
				
				// links, photos, videos, stamps, and achievements single page
				
				// geo profiles
				array('pattern' => '/{profile_id}/', 'path' => 'Profile/mixed'),
				array('pattern' => '/{profile_id}/{profile_child}/', 'path' => 'Profile/hasChild'),
				array('pattern' => '/{profile_id}/{profile_child}/{profile_view}/', 'path' => 'Profile/hasChild')
			)
	),

	API_HOST => array(
		'prefix' => API_HOST_PATH,
		'routes' => array(
				array(
					'name' => null, // named routes
					'title' => null, // named routes
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