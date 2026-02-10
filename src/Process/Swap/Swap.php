<?php

namespace Irmmr\RTLCss\Process\Swap;

use Sabberworm\CSS\Rule\Rule;

/**
 * class Swap
 *
 * @package Irmmr\RTLCss
 */
abstract class Swap
{
    /**
     * class constructor
     *
     * @param   mixed   $value      value of the rule
     * @param   Rule    $rule       get rule
     * @param   string  $property   name of property
     */
    public function __construct(
        protected mixed $value,
        protected Rule $rule,
        protected string $property
    ) {}

    /**
     * swap action
     *
     * @param mixed   $value
     * @param Rule    $rule
     * @param string  $property
     *
     * @return void
     */
    public static function swap(mixed $value, Rule $rule, string $property): void
    {
        $swap = new static($value, $rule, $property);
        $swap->apply();
    }

    /**
     * apply swap values
     *
     * @return void
     */
    abstract public function apply(): void;
}