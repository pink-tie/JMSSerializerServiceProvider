<?php

namespace JMS\SerializerServiceProvider;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistry;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * JMS Serializer Bundle integration for Silex.
 *
 * @author Marijn Huizendveld <marijn@pink-tie.com>
 */
class SerializerServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {

        $app['serializer'] = $app->share(function() use ($app) {
            return SerializerBuilder::create()
            ->setCacheDir($app['serializer.cache.directory'])
            ->setDebug(false)
            ->build();
        });
    }

    public function boot(Application $app)
    {
    }
}
