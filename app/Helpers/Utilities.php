<?php

namespace App\Helpers;

class Utilities
{

    static function validateInstagramLink($string)
    {
        if (filter_var($string, FILTER_VALIDATE_URL)) {
            if (strpos($string, 'instagram.com/p/') !== false) {
                return true;
            }
        }
        return false;
    }

    static function checkIsNumber($number)
    {
        $data = ['status' => false];
        $number = faToEn($number);
        if (is_numeric($number)) {
            $data['number'] = $number;
            $data['status'] = true;
        } else {
            $data['message'] = 'کد اشتباه است.';
        }
        return $data;
    }

    static function faToEn($string)
    {
        return strtr($string, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }

    static function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

}
