<?php

namespace JMS\SerializerServiceProvider;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistry,
    JMS\Serializer\Handler\DateHandler;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * JMS Serializer integration for Silex.
 *
 * @author Marijn Huizendveld <marijn@pink-tie.com>
 * @author David Raison <david@tentwentyfour.lu>
 */
class SerializerServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {

        /**
         * We're defining serializer.builder here, so that a user could redefine his own
         * serializer later inside the app using $app->share()
         */
        $app['serializer.builder'] = $app->share(function() use ($app){
            if(!isset($app['serializer.cache.directory'])){
                throw new \Exception(
                    'Could not register SerializerServiceProvider, setting serializer.cache.directory is mandatory!'
                );
            }
            return SerializerBuilder::create()
            ->addDefaultHandlers()
            ->setCacheDir($app['serializer.cache.directory']);
        });

        $app['serializer'] = $app->share(function() use ($app) {

            if(isset($app['serializer.date_time_handler.format'])){
                // Set a default for the timezone
                if(!isset($app['serializer.date_time_handler.timezone'])){
                    $app['serializer.date_time_handler.timezone'] = 'UTC';
                }

                $app['serializer.builder']
                ->configureHandlers(function(HandlerRegistry $registry) use ($app) {
                    $registry->registerSubscribingHandler(new DateHandler(
                        $app['serializer.date_time_handler.format'],
                        $app['serializer.date_time_handler.timezone']
                    ));
                });
            }

            if(!isset($app['serializer.debug'])){
                $app['serializer.debug'] = false;
            }

            return $app['serializer.builder']
            ->setDebug($app['serializer.debug'])
            ->build();
        });
    }

    public function boot(Application $app)
    {
    }
}
