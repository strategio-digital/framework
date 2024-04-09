<?php
declare(strict_types=1);

namespace Megio\Collection\Helper;

use Megio\Database\Interface\IJoinable;

class JoinableLabel
{
    /**
     * @param array<string, mixed> $data
     * @param class-string $className
     * @throws \ReflectionException
     */
    public static function fromArray(array $data, string $className): string
    {
        $instance = new $className();
        $method = new \ReflectionMethod($className, 'getJoinableLabel');
        
        /** @var array{fields: string[], format: string} $describer */
        $describer = $method->invoke($instance);
        unset($instance);
        
        $values = [];
        foreach ($describer['fields'] as $field) {
            if (array_key_exists($field, $data)) {
                $values[] = $data[$field];
            }
        }

        return sprintf($describer['format'], ...$values);
    }
    
    public static function fromEntity(IJoinable $entity): string
    {
        $ref = new \ReflectionClass($entity);
        $describer = $entity->getJoinableLabel();
        
        $values = [];
        foreach ($describer['fields'] as $field) {
            if ($ref->hasProperty($field)) {
                $values[] = $ref->getProperty($field)->getValue($entity);
            }
        }
        
        return sprintf($describer['format'], ...$values);
    }
}