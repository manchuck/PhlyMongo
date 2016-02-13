<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace PhlyMongo;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MongoCollectionFactory implements FactoryInterface
{
    /**
     * @var string
     */
    protected $collectionName;

    /**
     * @var string
     */
    protected $database;

    public function __construct($collectionName, $dbService)
    {
        $this->collectionName   = $collectionName;
        $this->database         = $dbService;
    }

    /**
     * @param ServiceLocatorInterface $services
     * @return \MongoDB\Collection
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /** @var \MongoDB\Database $db */
        $db = $services->get($this->database);
        return $db->selectCollection($this->collectionName);
    }
}
