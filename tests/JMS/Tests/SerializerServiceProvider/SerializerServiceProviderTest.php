<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JMS\Tests\SerializerServiceProvider;

use DateTime;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Silex\Application;
use JMS\SerializerServiceProvider\SerializerServiceProvider;

/**
 * SerializerServiceProvider test cases.
 *
 * @author Marijn Huizendveld <marijn@pink-tie.com>
 */
class SerializerServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    private $cache;

    public function setup()
    {
        $this->cache = sys_get_temp_dir()."/SerializerServiceProviderTest";

        if (file_exists($this->cache)) {
            $this->dropMetaDataCache($this->cache);
        }

        mkdir($this->cache);
    }

    public function teardown()
    {
        if (file_exists($this->cache)) {
            $this->dropMetaDataCache($this->cache);
        }
    }

    public function testRegister()
    {
        $app = new Application();

        $app->register(new SerializerServiceProvider(), array(
            'serializer.cache.directory' => $this->cache
        ));

        $this->assertInstanceOf("JMS\Serializer\Serializer", $app['serializer']);

        return $app;
    }

    /**
     * @depends testRegister
     */
    public function testSerialize(Application $app)
    {
        $fabien = new SerializableUser(1, "Fabien Potencier", new DateTime("2005-10-01T00:00:00+0000"));
        $fabienJson = '{"id":1,"name":"Fabien Potencier","created":"2005-10-01T00:00:00+0000"}';
        $fabienXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <id>1</id>
  <name><![CDATA[Fabien Potencier]]></name>
  <created>2005-10-01T00:00:00+0000</created>
</result>

XML;

        $this->assertEquals($fabienJson, $app['serializer']->serialize($fabien, "json"));
        $this->assertEquals($fabienXml, $app['serializer']->serialize($fabien, "xml"));
        $this->assertEquals($fabien, $app['serializer']->deserialize($fabienJson, "JMS\Tests\SerializerServiceProvider\SerializableUser", "json"));
        $this->assertEquals($fabien, $app['serializer']->deserialize($fabienXml, "JMS\Tests\SerializerServiceProvider\SerializableUser", "xml"));
        $this->assertFileExists($this->cache."/JMS-Tests-SerializerServiceProvider-SerializableUser.cache.php");
    }

    private function dropMetaDataCache($directory)
    {
        $iterator = new RecursiveDirectoryIterator($directory);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            if ($file->isDir()){
                @rmdir($file->getRealPath());
            } else {
                @unlink($file->getRealPath());
            }
        }

        @rmdir($directory);
    }
}
