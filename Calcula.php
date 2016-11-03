<?php
/**
 * source: https://gist.github.com/bainternet/5756049
 * source: http://codelinks.pachanka.org/post/95587797478/php-words-to-numbers-function
 */
class Calcula {

    public static $hyphen = '-';
    public static $conjunction = ' and ';
    public static $separator = ', ';
    public static $negative = 'negative ';
    public static $decimal = ' point ';
    public static $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion'
    );
    public static $dictionary2 = array(
        'zero' => '0',
        'one' => '1',
        'two' => '2',
        'three' => '3',
        'four' => '4',
        'five' => '5',
        'six' => '6',
        'seven' => '7',
        'eight' => '8',
        'nine' => '9',
        'ten' => '10',
        'eleven' => '11',
        'twelve' => '12',
        'thirteen' => '13',
        'fourteen' => '14',
        'fifteen' => '15',
        'sixteen' => '16',
        'seventeen' => '17',
        'eighteen' => '18',
        'nineteen' => '19',
        'twenty' => '20',
        'thirty' => '30',
        'forty' => '40',
        'fifty' => '50',
        'sixty' => '60',
        'seventy' => '70',
        'eighty' => '80',
        'ninety' => '90',
        'hundred' => '100',
        'thousand' => '1000',
        'million' => '1000000',
        'billion' => '1000000000',
        'and' => '',
    );

    public function wordsToNumber($data) {
        // Replace all number words with an equivalent numeric value
        $data = strtr(
                $data, self::$dictionary2
        );

        // Coerce all tokens to numbers
        $parts = array_map(
                function ($val) {
            return floatval($val);
        }, preg_split('/[\s-]+/', $data)
        );

        $stack = new SplStack; // Current work stack
        $sum = 0; // Running total
        $last = null;

        foreach ($parts as $part) {
            if (!$stack->isEmpty()) {
                // We're part way through a phrase
                if ($stack->top() > $part) {
                    // Decreasing step, e.g. from hundreds to ones
                    if ($last >= 1000) {
                        // If we drop from more than 1000 then we've finished the phrase
                        $sum += $stack->pop();
                        // This is the first element of a new phrase
                        $stack->push($part);
                    } else {
                        // Drop down from less than 1000, just addition
                        // e.g. "seventy one" -> "70 1" -> "70 + 1"
                        $stack->push($stack->pop() + $part);
                    }
                } else {
                    // Increasing step, e.g ones to hundreds
                    $stack->push($stack->pop() * $part);
                }
            } else {
                // This is the first element of a new phrase
                $stack->push($part);
            }

            // Store the last processed part
            $last = $part;
        }

        return $sum + $stack->pop();
    }

    public function numberToWord($number) {
        if (!is_numeric($number))
            return false;
        $string = '';
        switch (true) {
            case $number < 21:
                $string = self::$dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = self::$dictionary[$tens];
                if ($units) {
                    $string .= self::$hyphen . self::$dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = self::$dictionary[$hundreds] . ' ' . self::$dictionary[100];
                if ($remainder) {
                    $string .= self::$conjunction . self::numberToWord($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::numberToWord($numBaseUnits) . ' ' . self::$dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? self::$conjunction : self::$separator;
                    $string .= self::numberToWord($remainder);
                }
                break;
        }
        return $string;
    }

}
