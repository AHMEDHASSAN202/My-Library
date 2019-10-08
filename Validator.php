<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Library;


class Validator
{

    public function color($attribute, $value, $parameters, $validator)
    {
        return strpos($value, '#') !== false AND strlen($value) === 7;
    }


    public function slug($attribute, $value, $parameters, $validator)
    {
        return verifySlug($value);
    }


    public function customValidateEmail($attribute, $value, $parameters, $validator)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }


    public function phoneNumber($attribute, $value, $parameters, $validator)
    {
        return substr($value, 0, 2) == '01';
    }

}