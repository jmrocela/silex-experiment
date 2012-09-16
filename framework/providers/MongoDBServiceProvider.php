<?php
/**
 * @https://github.com/madiedinro/MongoExtension
 */
namespace DRodin\Extension;


use Silex\Application;
use Silex\ServiceProviderInterface;

use Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\MongoDB\Connection,
    Doctrine\ODM\MongoDB\Configuration,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;


/**
 * Doctrine ODM (MongoDB) extension for the Silex framework. 
 * 
 * @author Dmitry Rodin (madiedinro@gmail.com)
 * 
 */
class MongoDBServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mongo.options'] = array_replace(array(
            'dbname'   => null
        ), isset($app['mongo.options']) ? $app['mongo.options'] : array());

        $app['mongo'] = $app->share(function ($app) {
            return DocumentManager::create(
                new Connection(
                    isset($app['mongo.options']['server']) ? $app['mongo.options']['server'] : null
                ),
                $app['mongo.config']
            );
        });

        $app['mongo.config'] = $app->share(function ($app) {
            $config = new Configuration();

            if(isset($app['mongo.options']['dbname']))
            {
                $config->setDefaultDB($app['mongo.options']['dbname']);
            }

            $config->setProxyDir($app['mongo.common.proxy_dir']);
            $config->setProxyNamespace('Proxies');

            $config->setHydratorDir($app['mongo.common.hydrator_dir']);
            $config->setHydratorNamespace('Hydrators');

            AnnotationDriver::registerAnnotationClasses();

            $config->setMetadataDriverImpl(new AnnotationDriver($app['mongo.common.documents_dir']));

            return $config;

        });

        if (isset($app['mongo.common.class_path'])) {
            $app['autoloader']->registerNamespace('Doctrine\\Common', $app['mongo.common.class_path']);
        }

        if (isset($app['mongo.mongodb.class_path'])) {
            $app['autoloader']->registerNamespace('Doctrine\\MongoDB', $app['mongo.mongodb.class_path']);
        }

        if (isset($app['mongo.mongodbodm.class_path'])) {
            $app['autoloader']->registerNamespace('Doctrine\ODM\MongoDB', $app['mongo.mongodbodm.class_path']);
        }
        if (isset($app['mongo.common.documents_dir'])) {
            $app['autoloader']->registerNamespace('Documents', $app['mongo.common.documents_dir']);
        }
    }

    public function boot(Application $app) {}
}