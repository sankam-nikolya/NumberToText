<?php

class ConverterUkr implements ConverterInterface
{
    public function convert($number)
    {
        if (!is_int($number)) {
            throw new InvalidArgumentException('You enter not integer: ' . $number);
        }

        $num20 = [
            '', 'один', 'два', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять', 'десять', 'одинадцять', 'дванадцять',
            'тринадцять', 'чотирнадцять', 'п\'ятнадцять', 'шістнадцять', 'сімнадцять', 'вісімнадцять', 'дев\'ятнадцять'
        ];
        $num12 = ['одна', 'дві'];
        $tens = ['двадцять', 'тридцять', 'сорок', 'п\'ятдесят', 'шістдесят', 'сімдесят', 'вісімдесят', 'дев\'яносто'];
        $hundreds = ['сто', 'двісті', 'триста', 'чотириста', 'п\'ятсот', 'шістсот', 'сімсот', 'вісімсот', 'дев\'ятсот'];
        $units = [['тисяча', 'тисячі', 'тисяч'], ['мільйон', 'мільйони', 'мільйонів']];

        $parts = explode(',', (string)number_format($number));
        $partsNumber = count($parts);
        $result = [];
        $partNumber = 0;

        $i = 0;
        while (!empty($parts)) {

            $num = floor($parts[$i] / 100);
            $remainder = $parts[$i] % 100;

            if ($num >= 1 || ($partsNumber > 1 && $num >= 1)) {
                $result[] = $hundreds[$num - 1];
                $parts[$i] -= $num * 100;
                $partNumber += $num * 100;
            }

            if ($remainder < 20) {
                if (($remainder == 2 || $remainder == 1) && (($partsNumber == 2 && $i == 0) || ($partsNumber == 3 && $i == 1))) {
                    $result[] = $num12[$remainder - 1];
                    $partNumber += $remainder;
                } elseif ($remainder > 0) {
                    $result[] = $num20[$remainder];
                    $partNumber += $remainder;
                }
                $parts[$i] -= $remainder;
            } else {
                $result[] = $tens[((int)($remainder / 10)) - 2];
                $parts[$i] -= (int)($remainder / 10) * 10;
                $partNumber += (int)($remainder / 10) * 10;
            }

            if ($parts[$i] == 0) {
                unset($parts[$i]);
                ++$i;
                if ($i != 3 && $partsNumber > 2 || ($partsNumber == 2 && $i == 1)) {

                    if ($partsNumber == 3 && $i == 1) {
                        $k = 1;
                    }
                    if (($partsNumber == 3 && $i == 2) || ($partsNumber == 2 && $i == 1)) {
                        $k = 0;
                    }
                    switch (true) {
                        case (substr($partNumber,-1) == '1'):
                            $result[] = $units[$k][0];
                            break;
                        case (in_array(substr($partNumber,-1),['2','3','4'])):
                            $result[] = $units[$k][1];
                            break;
                        default:
                            $result[] = $units[$k][2];
                            break;
                    }
                    $partNumber = 0;
                }
            }
        }
        return implode(' ', $result);
    }

    /**
     * @return string - language code according to ISO 639-2/T
     */
    public
    function getLanguage()
    {
        return 'ukr';
    }
}

