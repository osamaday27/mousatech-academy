<?php

namespace App\Support;

class Money
{
    /** Format an integer amount of piasters. */
    public static function fmt(int $piasters): string
    {
        $s = number_format($piasters / 100, $piasters % 100 ? 2 : 0);

        return app()->getLocale() === 'ar' ? $s.' ج.م' : $s.' EGP';
    }
}
