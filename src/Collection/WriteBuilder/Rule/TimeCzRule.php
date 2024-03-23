<?php
declare(strict_types=1);

namespace Megio\Collection\WriteBuilder\Rule;

use Megio\Collection\WriteBuilder\Rule\Base\BaseRule;

class TimeCzRule extends BaseRule
{
    public function message(): string
    {
        return $this->message ?: "Field must be a valid time in Czech format. Example: 7:00:00";
    }
    
    /**
     * Return true if validation is passed
     * @return bool
     */
    public function validate(): bool
    {
        $value = $this->field->getValue();
        $nullable = array_filter($this->relatedRules, fn($rule) => $rule::class === NullableRule::class);
        
        if (count($nullable) !== 0 && $value === null) {
            return true;
        }
        
        if (!is_string($value)) {
            return false;
        }
        
        if (!preg_match('/^([0-9]|1[0-9]|2[0-3])(\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])){2}$/', $value)) {
            return false;
        }
        
        $date = \DateTime::createFromFormat('H:i:s', $value);
        
        if ($date instanceof \DateTime) {
            $date->setDate(1970, 1, 1);
            $this->field->setValue($date->format('Y-m-d H:i:s'));
            return true;
        }
        
        return false;
    }
}