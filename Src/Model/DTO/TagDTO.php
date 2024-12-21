<?php

namespace App\Src\Model\DTO;

class TagDTO
{
    public string $tagName;
    public string $tagCode;

    public function __construct(string $tagName, string $tagCode)
    {
        $this->tagName = $tagName;
        $this->tagCode = $tagCode;

    }
}
