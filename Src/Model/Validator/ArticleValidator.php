<?php

namespace App\Src\Model\Validator;

use InvalidArgumentException;
use Respect\Validation\Validator as v;

class ArticleValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        $newsId = 'newsId';
        $title = 'title';
        $slug = 'slug';
        $content = 'content';
        $categoryId = 'categoryId';
        $status = 'status';
        $publishDate = 'publishDate';
        $metaKeywords = 'metaKeywords';
        $metaDescription = 'metaDescription';
        $createdBy = 'createdBy';
        $utcCreatedDate = 'utcCreatedDate';
        $updatedBy = 'updatedBy';
        $utcUpdatedDate = 'utcUpdatedDate';
        $imageCover = 'imageCover';

        if (empty($data)) {
            throw new InvalidArgumentException(json_encode("Payload tidak boleh kosong"));
        }

        // Validate 'newsId' (optional integer)
        if (array_key_exists($newsId, $data) && !v::intType()->validate($data[$newsId])) {
            $errors[$newsId] = "'$newsId' must be an integer";
        }

        // Validate 'imageCover' (optional string, max 66 chars)
        if (array_key_exists($imageCover, $data)) {
            if (!v::intType()->validate($data[$imageCover])) {
                $errors[$imageCover] = "'$imageCover' must be an integer";
            }
        } else {
            $errors[$imageCover] = "'$imageCover' is required";
        }

        // Validate 'title' (required string, max 255 chars)
        if (!array_key_exists($title, $data) || !v::stringType()->length(1, 255)->validate($data[$title])) {
            $errors[$title] = "'$title' is required and must be a string with a maximum length of 255 characters";
        }

        // Validate 'slug' (required string, max 255 chars)
        if (!array_key_exists($slug, $data) || !v::stringType()->length(1, 255)->validate($data[$slug])) {
            $errors[$slug] = "'$slug' is required and must be a string with a maximum length of 255 characters";
        }

        // Validate 'content' (required string)
        if (!array_key_exists($content, $data) || !v::stringType()->validate($data[$content])) {
            $errors[$content] = "'$content' is required and must be a string";
        }

        // Validate 'categoryId' (required integer)
        if (!array_key_exists($categoryId, $data) || !v::intType()->validate($data[$categoryId])) {
            $errors[$categoryId] = "'$categoryId' is required and must be an integer";
        }

        // Validate 'status' (required enum, could be draft or published)
        if (!array_key_exists($status, $data) || !in_array($data[$status], ['draft', 'published'], true)) {
            $errors[$status] = "'$status' is required and must be either 'draft' or 'published'";
        }

        // Validate 'publishDate' (optional date)
        if (array_key_exists($publishDate, $data) && !v::date()->validate($data[$publishDate])) {
            $errors[$publishDate] = "'$publishDate' must be a valid date";
        }

        // Validate 'metaKeywords' (optional string, max 45 chars)
        if (array_key_exists($metaKeywords, $data) && !v::stringType()->length(null, 45)->validate($data[$metaKeywords])) {
            $errors[$metaKeywords] = "'$metaKeywords' must be a string with a maximum length of 45 characters";
        }

        // Validate 'metaDescription' (optional string, max 140 chars)
        if (array_key_exists($metaDescription, $data) && !v::stringType()->length(null, 140)->validate($data[$metaDescription])) {
            $errors[$metaDescription] = "'$metaDescription' must be a string with a maximum length of 140 characters";
        }

        // Validate 'createdBy' (required string)
        // if (!array_key_exists($createdBy, $data) || !v::stringType()->validate($data[$createdBy])) {
        //     $errors[$createdBy] = "'$createdBy' is required and must be a string";
        // }

        // Validate 'utcCreatedDate' (required string, valid UTC date format)
        // if (!array_key_exists($utcCreatedDate, $data) || !v::stringType()->validate($data[$utcCreatedDate])) {
        //     $errors[$utcCreatedDate] = "'$utcCreatedDate' is required and must be a valid UTC date string";
        // }

        // Validate 'updatedBy' (optional string)
        if (array_key_exists($updatedBy, $data) && !v::stringType()->validate($data[$updatedBy])) {
            $errors[$updatedBy] = "'$updatedBy' must be a string";
        }

        // Validate 'utcUpdatedDate' (optional string, valid UTC date format)
        if (array_key_exists($utcUpdatedDate, $data) && !v::stringType()->validate($data[$utcUpdatedDate])) {
            $errors[$utcUpdatedDate] = "'$utcUpdatedDate' must be a valid UTC date string";
        }

        // If there are any validation errors, throw an exception
        if (!empty($errors)) {
            throw new InvalidArgumentException(json_encode($errors));
        }
    }
}
