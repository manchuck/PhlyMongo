<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

use MongoDB\Client;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MongoClientFactory implements FactoryInterface
{
    /**
     * Server connection string
     *
     * @var string
     */
    protected $server = 'mongodb://localhost:27017';

    /**
     * Connection options
     *
     * @var array
     */
    protected $options = [
        'connect' => true
    ];

    /**
     * Driver-specific options
     *
     * @var array
     */
    protected $driverOptions = [];

    public function __construct($server = null, array $options = null, array $driverOptions = null)
    {
        if (null !== $server) {
            $this->server = $server;
        }

        if (null !== $options) {
            $this->options = $options;
        }

        if (null !== $driverOptions) {
            $this->driverOptions = $driverOptions;
        }
    }

    public function createService(ServiceLocatorInterface $services)
    {
        return new Client($this->server, $this->options, $this->driverOptions);
    }
}
