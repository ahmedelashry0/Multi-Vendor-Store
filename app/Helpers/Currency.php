<?php

namespace App\Helpers;

class Currency
{
    public static function format($amount , $currency = null)
    {
        $formatter = new \NumberFormatter(config('app.local') , \NumberFormatter::CURRENCY)
    }
}
