<?php

namespace App\Src\Model\Validator;

use App\Src\Utility\Enum\PageStatus;
use InvalidArgumentException;
use Respect\Validation\Validator as v;

class PageValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        $parent = 'parent';
        $imageCover = 'imageCover';
        $title = 'title';
        $metaDescription = 'metaDescription';
        $metaKeywords = 'metaKeywords';
        $content = 'content';
        $status = 'status';
        $order = 'order';

        if (empty($data)) {
            throw new InvalidArgumentException(json_encode("Payload tidak boleh kosong"));
        }
        // Validate 'parent' (integer)
        if (array_key_exists($parent, $data)) {
            if (!v::intType()->validate($data[$parent])) {
                $errors[$parent] = "'$parent' must be an integer";
            }
        }

        if (array_key_exists($order, $data)) {
            if (!v::intType()->validate($data[$order])) {
                $errors[$order] = "'$order' must be an integer";
            }
        } else {
            $errors[$order] = "'$order' is required";
        }

        // Validate 'imageCover' (optional string, max 66 chars)
        if (array_key_exists($imageCover, $data)) {
            if (!v::intType()->validate($data[$imageCover])) {
                $errors[$imageCover] = "'$imageCover' must be an integer";
            }
        } else {
            $errors[$imageCover] = "'$imageCover' is required";
        }

        // Validate 'title' (required string, max 66 chars)
        if (!array_key_exists($title, $data) || !v::stringType()->length(1, 66)->validate($data[$title])) {
            $errors[$title] = "'$title' is required and must be a string with a maximum length of 66 characters";
        }

        // Validate 'metaDescription' (optional string, max 140 chars)
        if (array_key_exists($metaDescription, $data)) {
            if (!v::stringType()->length(null, 140)->validate($data[$metaDescription])) {
                $errors[$metaDescription] = "'$metaDescription' must be a string with a maximum length of 140 characters";
            }
        } else {
            $errors[$metaDescription] = "'$metaDescription' is required";
        }

        // Validate 'metaKeywords' (optional string, max 45 chars)
        if (array_key_exists($metaKeywords, $data)) {
            if (!v::stringType()->length(null, 45)->validate($data[$metaKeywords])) {
                $errors[$metaKeywords] = "'$metaKeywords' must be a string with a maximum length of 45 characters";
            }
        } else {
            $errors[$metaKeywords] = "'$metaKeywords' is required";

        }

        // Validate 'content' (optional string)
        if (array_key_exists($content, $data)) {
            if (!v::stringType()->validate($data[$content])) {
                $errors[$content] = "'$content' must be a string";
            }
        } else {
            $errors[$content] = "'$content' is required";
        }

        // Validate 'status' (required enum)
        if (!array_key_exists($status, $data) || !in_array($data[$status], PageStatus::all(), true)) {
            $errors[$status] = "'$status' is required and must be one of: " . implode(', ', PageStatus::all());
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(json_encode($errors));
        }
    }
}
