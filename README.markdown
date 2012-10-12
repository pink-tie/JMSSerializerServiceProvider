The `SerializerServiceProvider` provides a service for serializing
objects. This service provider uses the [`JMS\SerializerBundle`][1] for
serializing.

[![Build Status](https://secure.travis-ci.org/pink-tie/JMSSerializerServiceProvider.png)](http://travis-ci.org/pink-tie/JMSSerializerServiceProvider)


Parameters
----------

* `serializer.src_directory`: The directory where the
`JMS\SerializerBundle` source is located.

* `serializer.cache.directory`: The directory to use for storing the
metadata cache.

* `serializer.naming_strategy.seperator` (optional): The separator
string used when normalizing properties.

* `serializer.naming_strategy.lower_case` (optional): Boolean flag
indicating if the properties should be normalized as lower case strings.

* `serializer.date_time_handler.format` (optional): The format used to
serialize and deserialize `DateTime` objects. Refer to the [PHP
documentation for supported Date/Time formats][2].

* `serializer.date_time_handler.default_timezone` (optional): The
timezone to use when serializing and deserializing `DateTime` objects.
Refer to the [PHP documentation for a list of supported timezones][3].

* `serializer.disable_external_entities` (optional): Boolean flag
indicating if the serializer should disable external entities for the
XML serialization format.

Services
--------

* `serializer`: An instance of
`JMS\SerializerBundle\Serializer\Serializer`.

Registering
-----------

```php
<?php

$app = new Silex\Application();

$app->register(new JMS\SerializerServiceProvider\SerializerServiceProvider(), array(
    'serializer.src_directory' => 'path/to/vendor/jms/serializer-bundle',
    'serializer.cache.directory' => 'path/to/cache'
));
```

Usage
-----

Annotate the class you wish to serialize, refer to the [annotation
documentation][4]

```php
<?php

use JMS\SerializerBundle\Annotation;

// The serializer bundle doesn't need getters or setters
class Page
{
    /**
     * @Type("integer")
     */
    private $id;

    /**
     * @Type("string")
     */
    private $title;

    /**
     * @Type("string")
     */
    private $body;

    /**
     * @Type("DateTime")
     */
    private $created;

    /**
     * @Type("Author")
     */
    private $author;

    /**
     * @Type("boolean")
     */
    private $featured;
}
```

```php
<?php
use JMS\SerializerBundle\Annotation;

// The serializer bundle doesn't need getters or setters
class Author
{
    /**
     * @Type("string")
     */
    private $name;
}
```

The `SerializerServiceProvider` provider provides a `serializer`
service. Use it in your application to serialize and deserialize your
objects:

```php
<?php

use Silex\Application;
use JMS\SerializerServiceProvider\SerializerServiceProvider;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();

// Make sure that the PHP script can write in the cache directory and that
// the directory exists.
$app->register(new SerializerServiceProvider(), array(
    'serializer.src_directory' => __DIR__."/../vendor/jms/serializer-bundle/src",
    'serializer.cache.directory' => __DIR__."/../cache/serializer"
));

// only accept content types supported by the serializer via the assert method.
$app->get("/pages/{id}.{_format}", function ($id) use ($app) {
    // assume a page_repository service exists that returns Page objects.
    $page = $app['page_repository']->find($id);
    $format = $app['request']->getFormat();

    if (!$page instanceof Page) {
        $this->abort("No page found for id: $id");
    }

    return new Response($app['serializer']->serialize($page, $format), 200, array(
        "Content-Type" => $app['request']->getMimeType($format)
    ));
})->assert("_format", "xml|json")
    ->assert("id", "\d+");
```

License:
--------
This service provider is available under the [`MIT LICENSE`][5]. Please note
that the required `JMSSerializerBundle` is made available under the [`Apache 2
LICENCE`][6].

Credits:
--------

Allow me to thank [Johannes Schmitt][7] (@schmittjoh) for making the
`JMSSerializerBundle`. 

[1]: http://jmsyst.com/bundles/JMSSerializerBundle
[2]: http://php.net/manual/en/datetime.formats.php
[3]: http://php.net/manual/en/timezones.php
[4]: http://jmsyst.com/bundles/JMSSerializerBundle/master/reference/annotations
[5]: https://github.com/pink-tie/JMSSerializerServiceProvider/blob/release/0.1.0/LICENSE
[6]: https://github.com/schmittjoh/JMSSerializerBundle/blob/master/Resources/meta/LICENSE
[7]: http://jmsyst.com
