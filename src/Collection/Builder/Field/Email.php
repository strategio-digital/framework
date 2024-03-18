<?php
declare(strict_types=1);

namespace Megio\Collection\Builder\Field;

use Megio\Collection\Builder\Field\Base\BaseField;
use Megio\Collection\Builder\Field\Base\FieldNativeType;
use Megio\Collection\Builder\Rule\EmailRule;

class Email extends BaseField
{
    public function renderer(): string
    {
        return 'email-renderer';
    }
    
    /**
     * @param string $name
     * @param string $label
     * @param \Megio\Collection\Builder\Rule\Base\IRule[] $rules
     * @param array<string, string|bool|null> $attrs
     * @param bool $mapToEntity
     * @param \Megio\Collection\Builder\Field\Base\FieldNativeType $type
     */
    public function __construct(
        protected string                     $name,
        protected string                     $label,
        protected array                      $rules = [],
        protected array                      $attrs = [],
        protected bool                       $mapToEntity = true,
        protected FieldNativeType            $type = FieldNativeType::EMAIL
    )
    {
        $rules[] = new EmailRule();
        parent::__construct($name, $label, $rules, $attrs, $mapToEntity, $type);
    }
}