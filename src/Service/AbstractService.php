<?php

namespace MobilityWork\Service;

use MobilityWork\Mock\Basic;

abstract class AbstractService
{
    /**
     * Get an instance of the client used to access to the service
     *
     * @return mixed
     */
    abstract protected function getClient();

    /** MOCK **/
    final protected function getServiceManager(): Basic {
        return new Basic();
    }

    final protected function getEntityRepository($type): Basic {
        return new Basic();
    }
}