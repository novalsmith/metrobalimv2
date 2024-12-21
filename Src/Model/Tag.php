<?php

namespace App\Src\Model;

use App\Src\Utility\Helper\UtilityHelper;

class Tag
{

    private string $tagCode;
    private string $tagName;

    public function __construct(array $data)
    {
        $this->tagName = $data['tagName'] ?? "";
        $this->tagCode = UtilityHelper::slugify($data['tagName']);
    }

    public function getTagCode(): string
    {
        return $this->tagCode;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }
}
