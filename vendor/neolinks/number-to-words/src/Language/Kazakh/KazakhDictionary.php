<?php

namespace NumberToWords\Language\Kazakh;

use NumberToWords\Language\Dictionary;

class KazakhDictionary implements Dictionary
{
    const LOCALE               = 'kk';
    const LANGUAGE_NAME        = 'Kazakh';
    const LANGUAGE_NAME_NATIVE = 'Қазақша';

    private static $units = [
        0 => '',
        1 => 'бір',
        2 => 'екі',
        3 => 'үш',
        4 => 'төрт',
        5 => 'бес',
        6 => 'алты',
        7 => 'жеті',
        8 => 'сегіз',
        9 => 'тоғыз'
    ];

    private static $tens = [
        0 => '',
        1 => 'он',
        2 => 'жиырма',
        3 => 'отыз',
        4 => 'қырық',
        5 => 'елу',
        6 => 'алпыс',
        7 => 'жетпіс',
        8 => 'сексен',
        9 => 'тоқсан'
    ];

    private static $hundred = 'жүз';

    /** @var array<array<string>>  */
    public static $currencyNames = [
        'KZT' => [
            [0, 'тенге', 'тенге', 'тенге'],
            [1, 'тиын', 'тиына', 'тиынов']
        ]
    ];

    /**
     * @return string
     */
    public function getAnd()
    {
        return 'және';
    }

    /**
     * @return string
     */
    public function getZero(): string
    {
        return 'нөл';
    }

    /**
     * @return string
     */
    public function getMinus(): string
    {
        return 'минус';
    }

    /**
     * @param int $unit
     *
     * @return string
     */
    public function getCorrespondingUnit(int $unit): string
    {
        return self::$units[$unit];
    }

    /**
     * @param int $ten
     *
     * @return string
     */
    public function getCorrespondingTen(int $ten): string
    {
        return self::$tens[$ten];
    }

    // /**
    //  * @param int $teen
    //  *
    //  * @return string
    //  */
     public function getCorrespondingTeen(int $teen): string
     {

         return self::$tens[1] . ' ' . self::$units[$teen];
     }

    /**
     * @param int $hundred
     *
     * @return string
     */
    public function getCorrespondingHundred(int $hundred): string
    {
        return self::$units[$hundred] . ' ' . self::$hundred;
    }
}
