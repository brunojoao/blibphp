<?php

namespace Blib\Arr;

class BlibArray
{
    public static function changes(array $old = [], array $new = []): array
    {
        $out = [];
        $loop = function ($old, $new) use (&$loop) {
            $final = [];
            foreach ($old as $k => $v) {
                if (!isset($new[$k])) {
                    $new[$k] = null;
                }
                if (!is_array($v) && $v != $new[$k]) {
                    $final[$k] = $new[$k];
                }
                if (is_array($v)) {
                    $child = $loop($v, $new[$k]);
                    if (count($child)) {
                        $final[$k] = $child;
                    }
                }
            }
            return $final;
        };

        if (count($old)) {
            if (count($new)) {
                $out = $loop($old, $new);
                return $out;
            }
        }
        return $out;
    }
}
