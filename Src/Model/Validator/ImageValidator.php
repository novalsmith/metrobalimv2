<?php

namespace App\Src\Model\Validator;

use InvalidArgumentException;

class ImageValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        $file = 'fileName';
        $alt = 'alt';
        $desc = 'descriptons';

        // $uploadedFiles = $request->getUploadedFiles();

// Pastikan file ada dan tidak kosong
        if (empty($data[$file]) || !isset($data[$file]) || $data[$file]->getError() === UPLOAD_ERR_NO_FILE) {

            $errors[$alt] = 'No file uploaded or file is empty.';
        }

        // if (empty($data[$file])) {
        //     $errors[$file] = 'file tidak boleh kosong.';
        // }

        if (empty($data[$alt])) {
            $errors[$alt] = 'alt tidak boleh kosong.';
        }
        // elseif (!v::length(10, null)->validate($data[$alt])) {
        //     $errors[$alt] = 'alt harus memiliki minimal 5 karakter.';
        // }

        if (empty($data[$desc])) {
            $errors[$alt] = 'Description tidak boleh kosong.';
        }
        //  elseif (!v::length(120, null)->validate($data[$alt])) {
        //     $errors[$desc] = 'Description harus memiliki minimal 5 karakter.';
        // }

        if (!empty($errors)) {
            throw new InvalidArgumentException(json_encode($errors));
        }
    }
}
