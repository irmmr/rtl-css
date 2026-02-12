# RTL-CSS

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.2-blue.svg) ![License](https://img.shields.io/badge/license-MIT-green.svg) ![GitHub issues](https://img.shields.io/github/issues/irmmr/rtl-css.svg) ![GitHub stars](https://img.shields.io/github/stars/irmmr/rtl-css.svg)

RTL-CSS is a PHP module designed to automatically convert CSS styles from left-to-right (LTR) to right-to-left (RTL) direction. This is particularly useful for applications that require localization support for languages that read from right to left, such as Persian, Arabic or Hebrew.

RTL-CSS utilizes the [MyIntervals/PHP-CSS-Parser](https://github.com/MyIntervals/PHP-CSS-Parser) library for parsing CSS. This powerful library provides a robust and flexible way to work with CSS stylesheets, allowing us to efficiently analyze and manipulate CSS rules.
## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Directives](#directives)
- [Encoder](#encoder)

## Features

- Automatically converts directional CSS rules to their RTL equivalents.
- Intelligently flips layout-sensitive properties without breaking syntax.
- Normalizes legacy vendor-prefixed functions for modern CSS compatibility.
- Easy integration with existing PHP projects.

The flipper mirrors and transforms directional CSS properties, including:

- Directional flow (`direction`, `float`, `clear`)
- Text and layout alignment (`text-align`, `justify-*`)
- Spacing properties (`margin`, `padding`, `border-width`, `border-style`, `border-color`)
- Border radius adjustments (`border-radius`)
- Box and text shadows (`box-shadow`, `text-shadow`)
- Transforms (`transform`, `transform-origin`)
- Background (`background`, `background-position`, `background-image`)
- `object-position` and `perspective-origin`
- Cursor direction values (`cursor`)
- Transition properties

The engine also understands and correctly flips directional CSS functions:

- `linear-gradient()`
- `repeating-linear-gradient()`
- `calc()`

Gradient directions (angles and keyword-based directions such as to left / to right) are mirrored automatically for RTL layouts.

All legacy vendor-prefixed calc variants are normalized before parsing:
- -moz-calc() → calc()
- -webkit-calc() → calc()
- -ms-calc() → calc()

This ensures modern, standards-compliant output while maintaining compatibility with legacy CSS sources.

## Installation

To install RTL-CSS, you can clone the repository or include it in your project using Composer. Here are the steps for both methods:

### Via composer
```bash
composer require irmmr/rtlcss
```

If you're using Composer, you can add RTL-CSS as a dependency in your composer.json file:
```json
{
    "require": {
        "irmmr/rtl-css": "*"
    }
}
```

## Usage

To use RTL-CSS in your project, include the main PHP file and call the conversion function. Here’s a simple example:

```php
use Irmmr\RTLCss\Parser as RTLParser;
use Sabberworm\CSS\Parser;
use Sabberworm\CSS\Parsing\SourceException;

// it's very simple and I know this
$css_content = "div { float: left; }";

// parse css code
$css_parser = new Parser($css_content);

try {
    $css_tree = $css_parser->parse();
} catch (SourceException $e) {
    // Error occ
    return;
}

// create rtlcss parser
$rtlcss = new RTLParser($css_tree);

// parsing css rules and properties
$rtlcss->flip();

// get parsed css code ==> div {float: right;}
echo $css_tree->render();
```

## Directives
Directives are a set of instructions that you can use to create certain commands for the generated RTL code. For example, you can change or remove some selectors, or make it so that rtlcss does not modify them.

- `ignore`
- `remove`
- `raw`
- `rename`
- `discard`

#### Ignore
```css
/* rtl:ignore */
div {
  float: right;
  text-align: right;
  font-size: 15px;
}

/* rtl:ignore:margin-left */
a {
   margin-left: 10px;
   background-position: 10px;
}
```
```css
div {
  float: right;
  text-align: right;
  font-size: 15px;
}

a {
   margin-left: 10px;
   background-position: right 10px top 50%;
}
```

[https://github.com/irmmr/rtl-css/wiki/Directives](https://github.com/irmmr/rtl-css/wiki/Directives)

## Encoder
The Encoder is an additional safety layer designed to protect your CSS before it is processed by the underlying parser.

While **MyIntervals/PHP-CSS-Parser** handles most modern CSS correctly (including newer `rgb()` syntax), certain edge cases and vendor-specific functions may still be altered, normalized, or even removed during parsing.

Examples include:
- Legacy vendor-prefixed functions such as -moz-calc(), -webkit-calc(), -ms-calc()
- Complex or uncommon function patterns
- Syntax structures unrelated to RTL transformation

The purpose of the Encoder is simple:
> Preserve CSS integrity.

It temporarily encodes sensitive segments of CSS before parsing, ensuring that:
- Unsupported or non-standard syntax is not removed
- Vendor-specific constructs are not lost
- Unrelated rules remain untouched
- The flipper only modifies what is actually relevant to RTL logic

After processing, all encoded segments are safely restored.

```php
use Irmmr\RTLCss\Parser as RTLParser;
use Irmmr\RTLCss\Encode;
use Sabberworm\CSS\Parser;
use Sabberworm\CSS\Parsing\SourceException;

$css_content = "
#element {
    float: left;
    transform: translate3d(-webkit-calc((25%/2) * 10px), 50%, calc(((25%/2) * 10px)));
    box-shadow: 2px 2px 2px 1px rgb(0 0 0 / 20%);
}
";

// encode css code
$encoder     = new Encode($css_content);
$css_content = $encoder->encode();

// parse css code
$css_parser = new Parser($css_content);

try {
    $css_tree = $css_parser->parse();
} catch (SourceException $e) {
    // Error occ
    return;
}

// create rtlcss parser
$rtlcss = new RTLParser($css_tree);

// parsing css rules and properties
$rtlcss->flip();

// get parsed css code
$encoder->setEncoded( $css_tree->render() );

// get final css code
echo $encoder->decode();
```
Output:
```css
#element {
    float: right;
    transform: translate3d(calc(-1*(( 25% / 2 ) * 10px)),50%,calc(( ( 25% / 2 ) * 10px )));
    box-shadow: -2px 2px 2px 1px rgb(0 0 0 / 20%);
}
```

## Examples

```css
.example {
  display: inline-block;
  padding: 5px 10px 15px 20px;
  margin: 5px 10px 15px 20px;
  border-style: dotted dashed double solid;
  border-width: 1px 2px 3px 4px;
  border-color: red green blue black;
  box-shadow: -1em 0 0.4em gray, 3px 3px 30px black;
}
```
Output:
```css
.example {
  display: inline-block;
  padding: 5px 20px 15px 10px;
  margin: 5px 20px 15px 10px;
  border-style: dotted solid double dashed;
  border-width: 1px 4px 3px 2px;
  border-color: red black blue green;
  box-shadow: 1em 0 0.4em gray, -3px 3px 30px black;
}
```

## Note
I created this project by observing https://github.com/moodlehq/rtlcss-php. One of the reasons was that this project was no longer active and needed some improvements. I tried to add new sections to it and at the same time, I aimed to make it more similar to the original project https://github.com/MohammadYounes/rtlcss/. I don't need to mention that this entire project is modeled and copied from the projects I referred to.

The transformation logic of this library is based on the behavior and principles demonstrated by [RTLCSS](https://rtlcss.com/).
During development, the generated RTL output was continuously compared against the output produced by RTLCSS to ensure consistency, correctness, and predictable mirroring behavior.
