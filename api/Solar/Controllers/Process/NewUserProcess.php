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
				'login_facebook' => '/secure/auth/facebook'
			);
	}

	public function signup()
	{
		
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