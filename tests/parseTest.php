<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ParseTest extends TestCase
{
    private array $files = [
        "bg.json",
        "bgImage.json",
        "bgPosition.json",
        "directives.json",
        "objPosition.json",
        "perspectiveOrigin.json",
        "properties.json",
        "regression.json",
        "transformOrigin.json",
        "transforms.json",
        "values.json",
        "valuesSyntaxN.json",
        "variables.json",
        "fix-150.json",
    ];

    private function addSpacesAroundCSSValues(string $css): string {
        // Regular expression to match CSS rules and ensure spaces around curly braces
        $pattern = '/(\w+\s*\{\s*)([^}]+)(\s*\})/';
        $replacement = '$1 $2 $3';

        // Apply the regex replacement
        $css =  preg_replace($pattern, $replacement, $css);

        return str_replace('  ', ' ', $css);
    }

    private function parseAction(string $css, array $options = []): string
    {
        $rtl_render = new \Irmmr\RTLCss\Encode($css);
        $css = $rtl_render->encode();

        // trying to parse created css sources
        $css_parser = new \Sabberworm\CSS\Parser($css);

        try {
            $css_tree = $css_parser->parse();
        } catch (\Sabberworm\CSS\Parsing\SourceException $e) {
            return '';
        }

        $rtlcss = new \Irmmr\RTLCss\Parser($css_tree, $options);

        $rtlcss->flip();

        $rtl_render->setEncoded($css_tree->render());

        return $this->addSpacesAroundCSSValues( $rtl_render->decode() );
    }

    public function testParseEveryEntry(): void
    {
        foreach ($this->files as $file) {
            $content = file_get_contents(__DIR__ . '/data/' . $file);
            $json    = json_decode($content, true);

            foreach ($json as $e) {
                $input      = $this->parseAction($e['input']);
                $expected   = $this->addSpacesAroundCSSValues($e['expected']);

                $this->assertSame($expected, $input, $e['should']);
            }
        }
    }
}