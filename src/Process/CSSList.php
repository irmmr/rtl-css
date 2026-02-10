<?php

namespace Irmmr\RTLCss\Process;

use Irmmr\RTLCss\Options;
use Irmmr\RTLCss\Traits\OptionsIniTrait;
use Sabberworm\CSS\CSSList\CSSList as CList;
use Sabberworm\CSS\RuleSet\DeclarationBlock;
use Sabberworm\CSS\RuleSet\RuleSet;

/**
 * class CSSList
 *
 * process css list
 *
 * @package Irmmr\RTLCss\Process
 */
class CSSList
{
    use OptionsIniTrait;

    /**
     * list of every content
     * @var array
     */
    protected array $contents = [];

    /**
     * The function is a PHP constructor.
     *
     * @param CList     $list
     * @param Options   $options
     */
    public function __construct(protected CList $list, Options $options)
    {
        $this->options = $options;
    }

    /**
     * _
     * run the main constructor
     *
     * @param CList   $list
     * @param Options $options
     */
    public static function parse(CList $list, Options $options): void
    {
        $css = new CSSList($list, $options);
        $css->run();
    }

    /**
     * run this process with
     * applying all we need to our entry
     */
    public function run(): void
    {
        foreach ($this->list->getContents() as $node) {
            if ($node instanceof CList) {
                CSSList::parse($node, $this->options);
            }

            if ($node instanceof RuleSet) {
                RuleSets::parse($node, $this->options);
            }

            if ($node instanceof DeclarationBlock) {
                Declaration::parse($node, $this->options);
            }

            $this->contents[] = $node;
        }

        $this->list->setContents( $this->contents );
    }
}