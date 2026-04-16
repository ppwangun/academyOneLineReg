<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2020-09-14
 * Time: 23:57
 */

namespace NumberToWords\Language\Kazakh;

use NumberToWords\Language\ExponentGetter;

class KazakhExponentGetter implements ExponentGetter
{
    private static $exponent = [
        "",
        "мың",
        "миллион",
        "миллиард",
        "триллион",
        "квадриллион",
        "квинтиллион",
        "секстиллион",
        "септиллион",
        "октиллион",
        "нониллион",
        "дециллион",
        "недециллион",
        "дуодециллион",
        "тредециллион",
        "кваттуордециллион",
        "квиндекиллион",
        "сексдекиллион",
        "сентендециллион",
        "октодециллион",
    ];

    /**
     * @param int $power
     *
     * @return string
     */
    public function getExponent(int $power): string
    {
        return self::$exponent[$power];
    }
}
