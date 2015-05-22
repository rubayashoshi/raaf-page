<?php

namespace RAAFPAGE\UserBundle\Service;


class UserDataEncoder
{
    private static $extraDigit = 324;

    public static function encode($number)
    {
        return self::$extraDigit + $number;
    }

    /**
     * @param int $number
     * @return float
     */
    public static function decode($number)
    {
        return $number - self::$extraDigit;
    }
}
