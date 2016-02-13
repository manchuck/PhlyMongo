<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongoTest;

use PhlyMongo\MongoManagerFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class MongoManagerFactoryTest extends TestCase
{
    protected $services;

    public function setUp()
    {
        if (!extension_loaded('mongodb')) {
            $this->markTestSkipped('Mongodb extension is required to run tests');
        }

        $this->services = new ServiceManager();
    }

    public function testFactoryCreatesAMongoInstanceWhenNoConstructorParametersProvided()
    {
        $factory = new MongoManagerFactory();
        $mongo   = $factory->createService($this->services);
        $this->assertInstanceOf('MongoDB\Driver\Manager', $mongo);
    }

    public function testFactoryWillCreateAMongoInstanceBasedOnParameters()
    {
        $factory = new MongoManagerFactory('mongodb://localhost:27017', ['connect' => false]);
        $mongo   = $factory->createService($this->services);
        $this->assertInstanceOf('MongoDB\Driver\Manager', $mongo);
    }
}
