<?php

namespace Solar\Providers;

use Solar\View;
use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MustacheServiceProvider implements ServiceProviderInterface {

    public function register(Application $app)
    {
        $app['mustache'] = $app->share(function () use ($app) {
            $mustache = new \Mustache_Engine(array(
                'cache' => $app['mustache.cache_dir'],
                'loader' => new \Mustache_Loader_FilesystemLoader(
                    $app['mustache.template_dir'],
                    array('extension' => $app['mustache.extension'])
                ),
				'partials_loader' => new \Mustache_Loader_FilesystemLoader(
                    $app['mustache.partials_dir'],
                    array('extension' => $app['mustache.extension'])
                ),
				'escape' => function($value) {
					return $value;
					// why does it default to the line below?
					// return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
				}
            ));
            return $mustache;
        });

        // View factory service
        $app['view'] = $app->protect(function ($template, array $data = array()) use ($app) {
            $view = new View($template, array(), function ($view) use ($app) {
                if ($app['request_format'] == DEFAULT_CONTENT_TYPE) {   
                    return $app['mustache']->loadTemplate($view->template)->render((array) $view);
                } else {
                    return $response;
                }
            });
            // TODO: check if this pollutes the view container!
            return $view->with($data);
        });
    }

    public function boot(Application $app) {}
    
}