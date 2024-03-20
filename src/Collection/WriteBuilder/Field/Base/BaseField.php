<?php
declare(strict_types=1);

namespace Megio\Collection\WriteBuilder\Field\Base;

use Megio\Collection\WriteBuilder\WriteBuilder;
use Megio\Collection\WriteBuilder\Rule\Base\IRule;

abstract class BaseField implements IField
{
    protected WriteBuilder $builder;
    
    /**
     * @var string|int|float|bool|null|array<string,mixed>|UndefinedValue
     */
    protected string|int|float|bool|null|array|UndefinedValue $value;
    
    /** @var string[] */
    protected array $errors = [];
    
    /**
     * @param \Megio\Collection\WriteBuilder\Rule\Base\IRule[] $rules
     * @param array<string, string|int|float|bool|null> $attrs
     */
    public function __construct(
        protected string $name,
        protected string $label,
        protected array  $rules = [],
        protected array  $attrs = [],
        protected bool   $disabled = false,
        protected bool   $mapToEntity = true
    )
    {
        $this->value = new UndefinedValue();
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getLabel(): string
    {
        return $this->label;
    }
    
    public function isDisabled(): bool
    {
        return $this->disabled;
    }
    
    public function addRule(IRule $rule): void
    {
        $this->rules[] = $rule;
    }
    
    public function getRules(): array
    {
        return $this->rules;
    }
    
    /** @return array<string, string|int|float|bool|null> */
    public function getAttrs(): array
    {
        return $this->attrs;
    }
    
    public function mappedToEntity(): bool
    {
        return $this->mapToEntity;
    }
    
    /**
     * @return string|int|float|bool|null|array<string,mixed>|UndefinedValue
     */
    public function getValue(): string|int|float|bool|null|array|UndefinedValue
    {
        return $this->value;
    }
    
    /**
     * @param string|int|float|bool|null|array<string,mixed> $value
     */
    public function setValue(string|int|float|bool|null|array $value): void
    {
        $this->value = $value;
    }
    
    public function addError(string $message): void
    {
        $this->errors[] = $message;
    }
    
    public function setBuilder(WriteBuilder $builder): void
    {
        $this->builder = $builder;
    }
    
    public function getBuilder(): WriteBuilder
    {
        return $this->builder;
    }
    
    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'renderer' => $this->renderer(),
            'disabled' => $this->isDisabled(),
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'rules' => array_map(fn($rule) => $rule->toArray(), $this->getRules()),
            'attrs' => $this->getAttrs(),
            'value' => $this->getValue(),
            'errors' => $this->getErrors(),
        ];
    }
}