<?php

namespace App\Src\Model\Validator;

use InvalidArgumentException;

class TagValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        $tagName = 'tagName';

        if (empty($data[$tagName])) {
            $errors[$tagName] = 'Nama Tag tidak boleh kosong.';
        } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', $data[$tagName])) {
            $errors[$tagName] = 'Nama Tag harus berupa alfanumerik tanpa karakter khusus.';
        } elseif (strlen($data[$tagName]) < 3 || strlen($data[$tagName]) > 50) {
            $errors[$tagName] = 'Nama kategori harus memiliki panjang antara 3-50 karakter.';
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(json_encode($errors));
        }
    }
}
