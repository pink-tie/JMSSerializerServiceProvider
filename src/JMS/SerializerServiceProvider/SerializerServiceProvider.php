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

        $app['serializer'] = $app->share(function() use ($app) {
            return SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(function(HandlerRegistry $registry) use ($app) {
                $registry->registerSubscribingHandler(new DateHandler(
                    $app['serializer.date_time_handler.format'],
                    $app['serializer.date_time_handler.timezone']
                ));
            })
            ->setCacheDir($app['serializer.cache.directory'])
            ->setDebug(false)
            ->build();
        });
    }

    public function boot(Application $app)
    {
    }
}
