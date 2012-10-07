<?php

namespace Solar\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;
use ElasticSearch\Client;

/**
 * @brief       Opauth authentication library integration.
 * @author      Gigablah <gigablah@vgmdb.net>
 */
class ElasticSearchServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['search'] = $app->share(function () use ($app) {
            foreach ($app['elastic.indexes'] as $index => $type) {
            
            }
            return Client::connection(array(
                    'server' => $app['elastic.config']['server'],
                    'protocol' => $app['elastic.config']['protocol'],
                    'index' => $app['elastic.config']['index'],
                    'type' => $app['elastic.config']['type']
                ));
        });    
    }

    public function boot(Application $app)
    {
       
    }
}