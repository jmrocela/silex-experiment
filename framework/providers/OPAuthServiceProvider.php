<?php

use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OPAuthServiceProvider implements ServiceProviderInterface {

    public function register(Application $app)
    {
        
    }

    public function boot(Application $app) {}
	
}