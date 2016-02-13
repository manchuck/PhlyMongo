<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongoTest;

use PhlyMongo\HydratingMongoCursor;
use Zend\Hydrator\ObjectProperty;

class HydratingMongoCursorTest extends AbstractTestCase
{
    protected $hydrator;
    protected $prototype;

    public function setUp()
    {
        parent::setUp();
        $this->hydrator   = new ObjectProperty();
        $this->prototype  = new TestAsset\Foo;
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
        for ($i = 0; $i < 100; $i += 1) {
            $authorIndex = array_rand($authors);
            $title       = uniqid();
            $data = [
                'title'   => $title,
                'author'  => $authors[$authorIndex],
                'content' => str_repeat($title, $i + 1),
            ];
            $this->collection->insertOne($data);
        }
    }

    public function testConstructorRaisesExceptionOnInvalidPrototype()
    {
        $rootCursor = $this->collection->find();
        $this->setExpectedException('InvalidArgumentException');
        new HydratingMongoCursor($rootCursor, $this->hydrator, []);
    }

    public function tetHydratorIsAccessibleAfterInstantiation()
    {
        $rootCursor = $this->collection->find();
        $cursor = new HydratingMongoCursor($rootCursor, $this->hydrator, $this->prototype);
        $this->assertSame($this->hydrator, $cursor->getHydrator());
    }

    public function tetPrototypeIsAccessibleAfterInstantiation()
    {
        $rootCursor = $this->collection->find();
        $cursor = new HydratingMongoCursor($rootCursor, $this->hydrator, $this->prototype);
        $this->assertSame($this->prototype, $cursor->getPrototype());
    }

    public function testIterationReturnsClonesOfPrototype()
    {
        $rootCursor = $this->collection->find();
        $cursor = new HydratingMongoCursor($rootCursor, $this->hydrator, $this->prototype);
        foreach ($cursor as $item) {
            $this->assertNotSame($this->prototype, $item);
            $this->assertInstanceOf('PhlyMongoTest\TestAsset\Foo', $item);
            $this->assertInstanceOf('MongoId', $item->_id);
            $this->assertFalse(empty($item->title));
            $this->assertContains($item->author, $this->authors);
            $this->assertContains($item->title, $item->content);
        }
    }
}
