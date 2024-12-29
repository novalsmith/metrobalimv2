<?php

namespace App\Src\Interface;

use App\Src\Model\BaseModel;
use App\Src\Model\Page;

interface IPageRepository
{
    public function createPage(Page $data, string $userId): BaseModel;
    public function getPage(): array;
    public function getPageById(string $slug): ?Page;
}
