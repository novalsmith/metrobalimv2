<?php

namespace App\Src\Utility\Enum;

class PageStatus
{
    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';
    public const ARCHIVED = 'archived';
    public const REVIEW = 'review';

    /**
     * Get all valid statuses
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::DRAFT,
            self::PUBLISHED,
            self::ARCHIVED,
            self::REVIEW,
        ];
    }
}
