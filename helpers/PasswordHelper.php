<?php

namespace app\helpers;

/**
 * Class PasswordHelper
 *
 * @package app\helpers
 */
class PasswordHelper
{
    /**
     * Generate unique password hash
     */

    public static function generateCompiledPassHash($password, $salt)
    {
        return md5(md5($salt) . md5($password));
    }

    /**
     * Generate unique password salt
     */

    public static function generatePasswordSalt($len = 6)
    {
        $salt = '';

        for ($i = 0; $i < $len; $i++) {
            $num = rand(33, 126);

            $salt .= chr($num);
        }

        return $salt;
    }

    public static function checkComplexity($password)
    {
        /*** get the length of the password ***/
        if (($length = strlen($password)) == 0) {
            return 1;
        }

        $strength = 0;

        /*** check if password is not all lower case ***/
        if (strtolower($password) != $password) {
            $strength += 1;
        }

        /*** check if password is not all upper case ***/
        if (strtoupper($password) != $password) {
            $strength += 1;
        }

        /*** check string length is 8 -15 chars ***/
        if ($length >= 8 && $length <= 15) {
            $strength += 1;
        } elseif ($length >= 16 && $length <= 35) {
            /*** check if length is 16 - 35 chars ***/
            $strength += 2;
        } elseif ($length > 35) {
            /*** check if length greater than 35 chars ***/
            $strength += 3;
        }

        /*** get the numbers in the password ***/
        preg_match_all('/[0-9]/', $password, $numbers);
        $strength += count($numbers[0]);

        /*** check for special chars ***/
        preg_match_all('/[|!@#$%&*\/=?,;.:\-_+~^\\\]/', $password, $specialChars);
        $strength += sizeof($specialChars[0]);

        /*** get the number of unique chars ***/
        $chars = str_split($password);
        $numUniqueChars = sizeof(array_unique($chars));
        $strength += $numUniqueChars * 2;

        /*** strength is a number 1-10; ***/
        $strength = $strength > 99 ? 99 : $strength;
        $strength = floor($strength / 10 + 1);

        return $strength;
    }
}
