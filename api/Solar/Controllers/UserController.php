<?php

namespace Solar\Controllers;

use Solar\Controller;

class UserController extends Controller {

	public function auth()
	{
		return $_GET;
	}

	public function login()
	{
		return array(
				'form' => array(
	    				'_token' => $this->csrf->generateCsrfToken('signin'),
						'link_to' => array(
								'facebook' => '/secure/auth/facebook',
								'google' => '/secure/auth/google',
								'twitter' => '/secure/auth/twitter',
							)
					)
			);
	}

	public function signup()
	{
	    return array(
	    		'form' => array(
	    				'action' => '',
	    				'_token' => $this->csrf->generateCsrfToken('signup'),
	    				'username' => '',
	    				'email' => '',
	    				'error' => ''
    				)
	    	);
	}

	public function reset()
	{
	    return array(
	    		'form' => array(
	    				'action' => '',
	    				'_token' => $this->csrf->generateCsrfToken('reset'),
	    				'username' => '',
	    				'email' => '',
	    				'error' => ''
    				)
	    	);
	}

}

/*

namespace 

/user
	/profile
	/photos
		/create
		/read
		/update
		/delete
	/links
		/create
		/read
		/update
		/delete
	/status
		/create
		/read
		/update
		/delete
	/followers
		/add
		/remove
	/following
		/add
		/remove
	/search
	/login
	/logout
	
	model related
	/relationships*
	/create *
	/update *
	/delete *

NATIVE

FACEBOOK

GOOGLE

TWITTER

RELATIONSHIPS

LINKS

PHOTOS

STATUS

SEARCH

PROFILE

CRUD

*/