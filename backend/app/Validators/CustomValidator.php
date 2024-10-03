<?php

namespace App\Validators;

use App\Validators\Traits\CategoryTrait;
use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
    use CategoryTrait;

    public function validateFileExists($attribute, $value)
    {
        return file_exists($value);
    }
}
