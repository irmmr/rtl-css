<?php

namespace Irmmr\RTLCss;

use Sabberworm\CSS\Parsing\SourceException;
use Sabberworm\CSS\Rule\Rule;
use Sabberworm\CSS\Parser as CSSParser;

/**
 * class Helpers
 *
 * @package Irmmr\RTLCss
 * some main functions
 *
 * !! comments are generated by AI
 */
class Helpers
{
    /**
     * swap options
     * scope --> string
     * ignoreCase --> bool
     */
    public const SWAP_OPTIONS = ['scope' => '*', 'ignoreCase' => true];

    /**
     * The `compare` function compares two values while optionally ignoring case sensitivity in PHP.
     *
     * @param string    $what     what The `$what` parameter is the first value that you want to compare.
     * @param string    $to       to The `$to` parameter in the `compare` function represents the value that you want to
     * compare against.
     * @param bool      $ignoreCase  ignoreCase The `ignoreCase` parameter is a boolean flag that determines whether the
     * comparison should be case-insensitive. If `ignoreCase` is `true`, the comparison will be done in a
     * case-insensitive manner by converting both `$what` and `$to` to lowercase before comparing them. If
     * `ignore
     *
     * @return bool  `compare` function returns `true` if the values of `$what` and `$to` are equal (ignoring
     * case if `$ignoreCase` is true), and `false` otherwise.
     */
    public static function compare(string $what, string $to, bool $ignoreCase): bool
    {
        if ($ignoreCase) {
            return strtolower($what) === strtolower($to);
        }

        return $what === $to;
    }

    /**
     * The function `swap` in PHP swaps occurrences of two specified strings in a given string based on
     * certain options.
     *
     * @param string $value value The `value` parameter in the `swap` function represents the string in which you
     * want to swap occurrences of strings `$a` and `$b` based on the specified options.
     * @param string $a a The `swap` function you provided is a protected method that takes in a string
     * `$value`, two strings `$a` and `$b` to swap, and an optional array `$options` with default values.
     * The function uses regular expressions to find occurrences of either `$a` or `$b` in
     * @param string $b b The `swap` function takes in a string `$value`, and two strings `$a` and `$b` that
     * you want to swap in the `$value` string. Additionally, it accepts an optional array `` with
     * default values set for `'scope'` and `'ignoreCase'`.
     * @param array $options options The `swap` function you provided takes in four parameters:
     *
     * @return string The `swap` function returns a string after performing a replacement operation using
     * regular expressions.
     */
    public static function swap(string $value, string $a, string $b, array $options = self::SWAP_OPTIONS): string
    {
        $expr = preg_quote($a) . '|' . preg_quote($b);

        if (!empty($options['greedy'])) {
            $expr = '\\b(' . $expr . ')\\b';
        }

        $flags = !empty($options['ignoreCase']) ? 'im' : 'm';
        $expr  = "/$expr/$flags";

        return preg_replace_callback($expr, function($matches) use ($a, $b, $options) {
            return self::compare($matches[0], $a, !empty($options['ignoreCase'])) ? $b : $a;
        }, $value);
    }

    /**
     * The function `swapLeftRight` swaps the positions of the words 'left' and 'right' in a given string.
     *
     * @param string $value value The `swapLeftRight` function takes a string input `$value` and swaps the
     * occurrences of the word 'left' with 'right' in the string.
     *
     * @return string The `swapLeftRight` function is returning the result of calling the `swap` method
     * with the parameters `'left'` and `'right'` on the input string `$value`.
     */
    public static function swapLeftRight(string $value): string
    {
        return self::swap($value, 'left', 'right');
    }

    /**
     * The function `swapLtrRtl` swaps the direction of text from left-to-right to right-to-left in a given
     * string.
     *
     * @param string $value value The `value` parameter is a string that represents the text that you want to swap
     * from left-to-right (ltr) to right-to-left (rtl) or vice versa.
     *
     * @return string The `swapLtrRtl` function is returning the result of calling the `swap` method with
     * the input string `$value` and the parameters 'ltr' and 'rtl'.
     */
    public static function swapLtrRtl(string $value): string
    {
        return self::swap($value, 'ltr', 'rtl');
    }

    /**
     * split the input text using seprator
     *
     * @param string $input
     * @param string $seprator
     *
     * @return array
     */
    public static function splitTrim(string $input, string $seprator): array
    {
        $data = explode($seprator, $input);

        $data = array_filter($data, function ($i) {
            return !empty($i);
        });

        return array_map('trim', $data);
    }

    /**
     * extract rules from css code
     *
     * @param string $css
     *
     * @return array<Rule>
     */
    public static function extractRules(string $css): array
    {
        $raw_parser = new CSSParser('.wrapper{' . $css . '}');

        try {
            $raw_tree = $raw_parser->parse();
        } catch (SourceException $e) {
            return [];
        }

        return $raw_tree->getContents()[0]->getRules();
    }

    /**
     * get value string
     *
     * @param mixed     $value
     * @param string    $implode_seprator
     * @return  string
     */
    public static function getValueStr($value, string $implode_seprator = ' '): string
    {
        if (is_array($value)) {
            return implode($implode_seprator, $value);
        }

        return is_string($value) ? $value : $value->__toString() ?? '';
    }

    /**
     * check if string looks like direction
     *
     * @param   string $str
     * @return  bool
     */
    public static function hasDirection(string $str): bool
    {
        return preg_match('/\b(top|bottom|right|left)\b/i', $str);
    }
}