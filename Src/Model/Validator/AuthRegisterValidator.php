<?php

namespace App\Src\Model\Validator;

use InvalidArgumentException;
use Respect\Validation\Validator as v;

class AuthRegisterValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        $email = 'email';
        $password = 'password';
        $name = 'name';

        if (empty($data[$email])) {
            $errors[$email] = 'email tidak boleh kosong.';
        } elseif (!v::email()->validate($data['email'])) {
            $errors['email'] = 'Email tidak valid.';
        }

        if (empty($data[$password])) {
            $errors[$password] = 'Password tidak boleh kosong.';
        } elseif (!v::length(5, null)->validate($data[$password])) {
            $errors[$password] = 'Password harus memiliki minimal 5 karakter.';
        } elseif (!preg_match('/[a-zA-Z]/', $data[$password])) {
            $errors[$password] = 'Password harus mengandung setidaknya satu huruf.';
        } elseif (!preg_match('/\d/', $data[$password])) {
            $errors[$password] = 'Password harus mengandung setidaknya satu angka.';
        } elseif (!preg_match('/[\W_]/', $data[$password])) {
            $errors[$password] = 'Password harus mengandung setidaknya satu simbol.';
        }

        if (empty($data[$name])) {
            $errors[$name] = 'Nama tidak boleh kosong.';
        } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', $data[$name])) {
            $errors[$name] = 'Nama harus berupa alfanumerik tanpa karakter khusus.';
        } elseif (strlen($data[$name]) < 3 || strlen($data[$name]) > 50) {
            $errors[$name] = 'Nama harus memiliki panjang antara 3-50 karakter.';
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(json_encode($errors));
        }
    }
}
