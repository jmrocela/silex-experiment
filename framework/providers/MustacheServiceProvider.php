<?php

use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MustacheServiceProvider implements ServiceProviderInterface {

    public function register(Application $app)
    {
        $app['mustache'] = $app->share(function ($app) {
            return new Mustache_Engine();
        });
    }

    public function boot(Application $app) {}
	
}