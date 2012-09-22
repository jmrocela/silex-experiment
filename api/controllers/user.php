<?php

class UserController extends Solar\Controller {

	public function auth()
	{
		return array(
				'login_facebook' => '/login/facebook'
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