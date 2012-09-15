<?php

use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MongoDBServiceProvider implements ServiceProviderInterface {

    public function register(Application $app)
    {
        $mongo = new Mongo();
		$mongo->selectDB(MONGODB_NAME);
		
		$app['mongodb'] = $mongo;
		return $app;
    }

    public function boot(Application $app)
    {
        $app->before(function (Request $request) use ($app) {
		
        });

        $app->after(function (Request $request, Response $response) use ($app) {
		
        });
    }
	
}