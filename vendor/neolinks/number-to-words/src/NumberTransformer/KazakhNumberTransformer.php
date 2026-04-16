<?php

namespace NumberToWords\NumberTransformer;

use NumberToWords\Language\Kazakh\KazakhDictionary;
use NumberToWords\Language\Kazakh\KazakhExponentGetter;
use NumberToWords\Language\Kazakh\KazakhTripletTransformer;
use NumberToWords\Service\NumberToTripletsConverter;

class KazakhNumberTransformer implements NumberTransformer
{
    /**
     * @inheritdoc
     */
    public function toWords(int $number): string
    {
        $dictionary = new KazakhDictionary();
        $numberToTripletsConverter = new NumberToTripletsConverter();
        $tripletTransformer = new KazakhTripletTransformer($dictionary);
        $exponentInflector = new KazakhExponentGetter();

        $numberTransformer = (new NumberTransformerBuilder())
            ->withDictionary($dictionary)
            ->withWordsSeparatedBy(' ')
            ->transformNumbersBySplittingIntoTriplets($numberToTripletsConverter, $tripletTransformer)
            ->useRegularExponents($exponentInflector)
            ->build();

        return $numberTransformer->toWords($number);
    }
}
