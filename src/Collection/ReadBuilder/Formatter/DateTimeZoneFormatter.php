<?php
declare(strict_types=1);

namespace Megio\Collection\ReadBuilder\Formatter;

use Megio\Collection\ReadBuilder\Formatter\Base\BaseFormatter;

class DateTimeZoneFormatter extends BaseFormatter
{
    public function format(mixed $value): mixed
    {
        if ($value instanceof \DateTime || $value instanceof \DateTimeImmutable) {
            return [
                'value' => $value->format('Y-m-d H:i:s'),
                'zone_name' => $value->format('e'), // 'Europe/Prague'
            ];
        }
        
        return $value;
    }
}