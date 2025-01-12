<?php

namespace App\Src\Model\Validator;

use InvalidArgumentException;
use Respect\Validation\Validator as v;

class ArticlePayloadValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        $newsId = 'newsId';
        $categoryId = 'categoryId';
        $status = 'status';
        $keywords = 'keywords';
        $startDate = 'startDate';
        $endDate = 'endDate';

        if (empty($data)) {
            throw new InvalidArgumentException(json_encode("Payload tidak boleh kosong"));
        }

        // Validate 'newsId' (optional integer)
        if (array_key_exists($newsId, $data) && !v::intType()->validate($data[$newsId])) {
            $errors[$newsId] = "'$newsId' must be an integer";
        }

        // Validate 'categoryId' (required integer)
        if (array_key_exists($categoryId, $data) && !v::intType()->validate($data[$categoryId])) {
            $errors[$categoryId] = "'$categoryId' is required and must be an integer";
        }

        // Validate 'status' (required enum, could be draft or published)
        if (array_key_exists($status, $data) && !in_array($data[$status], ['draft', 'published'], true)) {
            $errors[$status] = "'$status' is required and must be either 'draft' or 'published'";
        }

        // Validate 'keywords' (optional string, max 45 chars)
        if (!array_key_exists($keywords, $data)) {
            $errors[$keywords] = "'$keywords' is required";
        } else if (!v::stringType()->length(5, null)->validate($data[$keywords])) {
            $errors[$keywords] = "'$keywords' must be a string with a minimum length of 5 characters";
        }

        if (array_key_exists($startDate, $data) && !v::stringType()->validate($data[$startDate])) {
            $errors[$startDate] = "'$startDate' must be a valid UTC date string";
        }

        if (array_key_exists($endDate, $data) && !v::stringType()->validate($data[$endDate])) {
            $errors[$endDate] = "'$endDate' must be a valid UTC date string";
        }

        // If there are any validation errors, throw an exception
        if (!empty($errors)) {
            throw new InvalidArgumentException(json_encode($errors));
        }
    }
}
