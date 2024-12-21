<?php

namespace App\Src\Model\Validator;

use InvalidArgumentException;

class CategoryValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        $categoryName = 'categoryName';
        $categoryId = 'categoryId';
        $parentId = 'parentId';

        if (empty($data[$categoryName])) {
            $errors[$categoryName] = 'Nama kategori tidak boleh kosong.';
        } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', $data[$categoryName])) {
            $errors[$categoryName] = 'Nama kategori harus berupa alfanumerik tanpa karakter khusus.';
        } elseif (strlen($data[$categoryName]) < 3 || strlen($data[$categoryName]) > 50) {
            $errors[$categoryName] = 'Nama kategori harus memiliki panjang antara 3-50 karakter.';
        }

        if (array_key_exists($parentId, $data)) {
            if (isset($data[$parentId]) && !is_numeric($data[$parentId])) {
                $errors[$parentId] = 'Parent harus berupa angka.';
            } else if (empty($data[$parentId])) {
                $errors[$parentId] = 'Parent tidak boleh kosong';
            }
        }

        if (array_key_exists($categoryId, $data)) {
            if (isset($data[$categoryId]) && !is_numeric($data[$categoryId])) {
                $errors[$categoryId] = 'ID Kategori harus berupa angka.';
            } else if (empty($data[$categoryId])) {
                $errors[$categoryId] = 'ID Kategori tidak boleh kosong';
            }
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(json_encode($errors));
        }
    }
}
