<?php

namespace App\Src\Model\Validator;

use InvalidArgumentException;
use Respect\Validation\Validator as v;

class ImageValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        $file = 'file';
        $alt = 'alt';
        $desc = 'descriptons';

        if (empty($data[$file])) {
            $errors[$file] = 'file tidak boleh kosong.';
        }

        if (empty($data[$alt])) {
            $errors[$alt] = 'alt tidak boleh kosong.';
        } elseif (!v::length(10, null)->validate($data[$alt])) {
            $errors[$alt] = 'alt harus memiliki minimal 5 karakter.';
        }

        if (empty($data[$desc])) {
            $errors[$alt] = 'Description tidak boleh kosong.';
        } elseif (!v::length(20, null)->validate($data[$alt])) {
            $errors[$desc] = 'Description harus memiliki minimal 5 karakter.';
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(json_encode($errors));
        }
    }
}