<?php

namespace App\Validators;

use App\Validators\Traits\CategoryTrait;
use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
    use CategoryTrait;


    public function validateFileExists($attribute, $value)
    {
        return file_exists($value) || file_exists(public_path($value));
    }

    public function validatePhoneNumber($attribute, $value)
    {
        return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
    }
}
