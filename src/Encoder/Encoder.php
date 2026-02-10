<?php

namespace Irmmr\RTLCss\Encoder;

/**
 * abstract class Encoder
 *
 * @package Irmmr\RTLCss\Encoder
 */
abstract class Encoder
{
    /**
     * encoded code
     * @var string
     */
    protected string $encoded = '';

    /**
     * class constructor
     *
     * @param string $code code holder
     */
    public function __construct(
        protected string $code = ''
    ) {}

    /**
     * get encoded code
     *
     * @return string
     */
    abstract public function encode(): string;

    /**
     * get decoded code
     *
     * @return string
     */
    abstract public function decode(): string;

    /**
     * get encoded code
     *
     * @return string
     */
    public function getEncoded(): string
    {
        return $this->encoded;
    }

    /**
     * update encoded code
     *
     * @param  string $encoded
     */
    public function setEncoded(string $encoded): void
    {
        $this->encoded = $encoded;
    }

    /**
     * get pure code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}