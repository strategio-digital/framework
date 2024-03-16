<?php
declare(strict_types=1);

namespace Megio\Extension\Doctrine\Logger;

use Psr\Log\AbstractLogger;

class SnapshotLogger extends AbstractLogger
{
    /** @var array<int, array{level: mixed, message: string, context: mixed[], timestamp: int}> */
    protected array $snapshots = [];
    
    /**
     * @param mixed[] $context
     */
    public function log(mixed $level, \Stringable|string $message, array $context = []): void
    {
        $this->snapshots[] = [
            'level' => $level,
            'message' => (string) $message,
            'context' => $context,
            'timestamp' => time(),
        ];
    }
    
    /**
     * @return array<int, array{duration:int}>
     */
    public function getQueries(): array
    {
        // TODO: https://github.com/contributte/doctrine-dbal/blob/master/src/Logger/SnapshotLogger.php
        return [];
    }
}