<?php

namespace Irmmr\RTLCss\Process\Swap;

use Irmmr\RTLCss\Manipulate;
use Sabberworm\CSS\Value\CSSFunction;
use Sabberworm\CSS\Value\RuleValueList;

/**
 * class SwapTransform
 *
 * @package Irmmr\RTLCss
 */
class SwapTransform extends Swap
{
    /**
     * apply swap values
     *
     * @return void
     */
    public function apply(): void
    {
        $value = $this->value;

        if ($value instanceof RuleValueList) {
            $items = $value->getListComponents();
        } else {
            $items = [$value];
        }

        foreach ($items as $item_key => $item) {
            if (!$item instanceof CSSFunction) {
                return;
            }

            $name   = $item->getName();
            $parts  = $item->getListComponents();

            // leave scales alone!
            if (str_contains($name, 'scale')) {
                continue;

            } else if (str_contains($name, 'rotate3d') || str_contains($name, 'translate3d')) {
                $keys = [0, 3];

                foreach ($keys as $key) {
                    if (isset($parts[$key])) {
                        Manipulate::negate($parts[$key]);
                    }
                }

            } elseif (str_contains($name, 'matrix3d')) {
                $keys = [1, 3, 4, 12];

                foreach ($keys as $key) {
                    if (isset($parts[$key])) {
                        Manipulate::negate($parts[$key]);
                    }
                }

            } elseif (str_contains($name, 'matrix')) {
                $keys = [1, 2, 4];

                foreach ($keys as $key) {
                    if (isset($parts[$key])) {
                        Manipulate::negate($parts[$key]);
                    }
                }

            } elseif (str_contains($name, 'translate')) {
                Manipulate::negate($parts[0]);

            } else {
                Manipulate::negate($item);
            }
        }
    }
}