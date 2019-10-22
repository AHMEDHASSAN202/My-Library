<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Library;


class Validator
{

    public function _color($attribute, $value, $parameters, $validator)
    {
        return strpos($value, '#') !== false AND strlen($value) === 7;
    }

    public function _email($attribute, $value, $parameters, $validator)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }


    public function _phone($attribute, $value, $parameters, $validator)
    {
        return substr($value, 0, 2) == '01';
    }

}