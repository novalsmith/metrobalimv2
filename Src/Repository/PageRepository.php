<?php

namespace App\Src\Repository;

use App\Src\Interface\IPageRepository;
use App\Src\Model\BaseModel;
use App\Src\Model\Page;
use App\Src\Utility\Config\Constant;

class PageRepository extends BaseRepository implements IPageRepository
{
    public function createPage(Page $data, string $userId): BaseModel
    {
        $params = [
            $data->parent,
            $data->imageCover,
            $data->title,
            $data->order,
            $data->slug,
            $data->metaDescription,
            $data->metaKeywords,
            $data->content,
            $data->status,
            $data->publishDate,
            $userId,
        ];
        return $this->executeQueryFetchObject(Constant::SPPage_UpsertPage, $params, BaseModel::class);
    }

    public function getPage(): array
    {
        return $this->executeQueryFetchAll(Constant::SPPage_GetPage, [], Page::class);

    }

    public function getPageById(string $slug): ?Page
    {
        $params = [
            $slug,
        ];
        return $this->executeQueryFetchObject(Constant::SPPage_GetPageById, $params, Page::class);
    }
}
