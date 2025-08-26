<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helper
{
    public static function generateUniqueId(string $prefix = 'POLL', int $length = 6): string
    {
        $numbers = mt_rand(10 ** ($length - 1), (10 ** $length) - 1);

        $letters = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);

        return strtoupper($prefix . '-' . $letters . $numbers);
    }
}
