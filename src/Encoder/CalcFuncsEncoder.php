<?php

namespace Irmmr\RTLCss\Encoder;

/**
 * class CalcFuncsEncoder
 *
 * replace -webkit-calc and -moz-calc with calc function in css
 * -webkit-calc, -moz-calc are dead!
 *
 * @package Irmmr\RTLCss\Encoder
 */
class CalcFuncsEncoder extends Encoder
{
    /**
     * pattern for legacy vendor-prefixed calc() functions
     *
     * @var string
     */
    protected string $pattern = '/-(moz|webkit)-calc\s*\(/i';

    /**
     * get encoded code
     *
     * @return string
     */
    public function encode(): string
    {
        return $this->encoded = preg_replace('/-(moz|webkit|ms)-calc\s*\(/', 'calc(', $this->code);
    }

    /**
     * get decoded code
     *
     * @return string
     */
    public function decode(): string
    {
        return $this->encoded;
    }
}