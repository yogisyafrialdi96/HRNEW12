<?php

namespace App\Helpers;

class TerbilangHelper
{
    /**
     * Convert number to Indonesian words
     * 
     * @param int|float $number
     * @param bool $addRupiah Add "rupiah" at the end
     * @return string
     */
    public static function terbilang($number, $addRupiah = true)
    {
        $number = (int) $number;
        
        if ($number < 0) {
            return 'minus ' . self::terbilang(abs($number), $addRupiah);
        }

        $words = [
            0 => 'nol',
            1 => 'satu',
            2 => 'dua',
            3 => 'tiga',
            4 => 'empat',
            5 => 'lima',
            6 => 'enam',
            7 => 'tujuh',
            8 => 'delapan',
            9 => 'sembilan',
            10 => 'sepuluh',
            11 => 'sebelas',
            12 => 'dua belas',
            13 => 'tiga belas',
            14 => 'empat belas',
            15 => 'lima belas',
            16 => 'enam belas',
            17 => 'tujuh belas',
            18 => 'delapan belas',
            19 => 'sembilan belas',
            20 => 'dua puluh',
            30 => 'tiga puluh',
            40 => 'empat puluh',
            50 => 'lima puluh',
            60 => 'enam puluh',
            70 => 'tujuh puluh',
            80 => 'delapan puluh',
            90 => 'sembilan puluh',
        ];

        $scales = [
            1000000000000 => 'triliun',
            1000000000 => 'miliar',
            1000000 => 'juta',
            1000 => 'ribu',
            100 => 'ratus',
        ];

        if ($number == 0) {
            return 'nol' . ($addRupiah ? ' rupiah' : '');
        }

        if ($number < 20) {
            $result = $words[$number];
        } elseif ($number < 100) {
            $result = $words[floor($number / 10) * 10] . ($number % 10 > 0 ? ' ' . $words[$number % 10] : '');
        } else {
            $result = '';

            foreach ($scales as $scale => $scaleName) {
                if ($number >= $scale) {
                    $scaleValue = floor($number / $scale);
                    $result .= self::terbilang($scaleValue, false) . ' ' . $scaleName . ' ';
                    $number %= $scale;
                }
            }

            if ($number > 0) {
                $result .= self::terbilang($number, false);
            }

            $result = trim($result);
        }

        if ($addRupiah) {
            $result .= ' rupiah';
        }

        return $result;
    }

    /**
     * Convert number to Indonesian words with rupiah format
     * Useful for displaying currency in documents
     * 
     * Example: 1500000 => "satu juta lima ratus ribu rupiah"
     * 
     * @param int|float $number
     * @return string
     */
    public static function terbilangRupiah($number)
    {
        return self::terbilang($number, true);
    }

    /**
     * Convert number to Indonesian words without rupiah
     * 
     * @param int|float $number
     * @return string
     */
    public static function terbilangAngka($number)
    {
        return self::terbilang($number, false);
    }
}
