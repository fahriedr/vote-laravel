<?php

namespace App\Helpers;

class IdGenerator
{
    public static function generate(string $prefix = 'POLL', int $length = 6): string
    {
        $numbers = mt_rand(10 ** ($length - 1), (10 ** $length) - 1);

        $letters = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);

        return strtoupper($prefix . '-' . $letters . $numbers);
    }
}
