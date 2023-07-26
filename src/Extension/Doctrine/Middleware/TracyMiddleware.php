<?php
/**
 * Copyright (c) 2023 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.dev, jz@strategio.dev)
 */
declare(strict_types=1);

namespace Saas\Extension\Doctrine\Middleware;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Logging\Driver as LoggingDriver;
use Saas\Extension\Doctrine\Logger\SnapshotLogger;

class TracyMiddleware implements Middleware
{
    protected SnapshotLogger $logger;
    
    public function __construct()
    {
        $this->logger = new SnapshotLogger();
    }
    
    public function wrap(Driver $driver): Driver
    {
        return new LoggingDriver($driver, $this->logger);
    }
    
    public function getLogger(): SnapshotLogger
    {
        return $this->logger;
    }
}