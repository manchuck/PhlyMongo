<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongoTest;

use MongoDB\Collection;
use MongoDB\Driver\Manager;
use PHPUnit_Framework_TestCase as TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var \MongoClient|\Mongo
     */
    protected $mongo;

    /**
     * @var \MongoDB
     */
    protected $db;

    /**
     * @var \MongoCollection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var array
     */
    protected $authors;

    public function setUp()
    {
        if (!extension_loaded('mongodb')) {
            $this->markTestSkipped('Mongodb extension is required to run tests');
        }

        $services     = Bootstrap::getServiceManager();
        $config       = $services->get('ApplicationConfig');
        $config       = $config['mongo'];
        $mongo        = new Manager($config['server']);
        $collection   = new Collection($mongo, $config['collection']);

        $this->mongo      = $mongo;
        $this->collection = $collection;

        $this->seedCollection();
    }

    protected function seedCollection()
    {
        $this->collection->drop();
        $this->authors = $authors = [
            'Matthew',
            'Mark',
            'Luke',
            'John',
        ];
        $this->items = [];
        for ($i = 0; $i < 100; $i += 1) {
            $authorIndex = array_rand($authors);
            $title       = uniqid();
            $data = [
                'title'   => $title,
                'author'  => $authors[$authorIndex],
                'content' => str_repeat($title, $i + 1),
            ];
            $this->collection->insertOne($data);
            $this->items[] = $data;
        }
    }
}
