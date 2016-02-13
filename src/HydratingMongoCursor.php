<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

use IteratorIterator;
use InvalidArgumentException;
use MongoDB\Driver\Cursor as MongoCursor;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Iterator\HydratingIteratorInterface;

class HydratingMongoCursor extends IteratorIterator implements HydratingIteratorInterface
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var mixed
     */
    protected $prototype;

    /**
     * HydratingMongoCursor constructor.
     *
     * @param MongoCursor $cursor
     * @param HydratorInterface $hydrator
     * @param $prototype
     */
    public function __construct(MongoCursor $cursor, HydratorInterface $hydrator, $prototype)
    {
        parent::__construct($cursor);
        $this->setHydrator($hydrator);
        $this->setPrototype($prototype);
    }

    /**
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @param object|string $prototype
     */
    public function setPrototype($prototype)
    {
        if (!is_object($prototype)) {
            throw new InvalidArgumentException(sprintf(
                'Prototype must be an object; received "%s"',
                gettype($prototype)
            ));
        }

        $this->prototype = $prototype;
    }

    /**
     * @return mixed
     */
    public function getPrototype()
    {
        return $this->prototype;
    }

    /**
     * @return mixed|object
     */
    public function current()
    {
        $result = parent::current();
        if (!is_array($result)) {
            return $result;
        }

        return $this->hydrator->hydrate($result, clone $this->prototype);
    }
}
