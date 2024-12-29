<?php

namespace App\Src\Service;

use App\Src\Interface\IPageRepository;
use App\Src\Interface\IPageService;
use App\Src\Model\BaseModel;
use App\Src\Model\Page;

class PageService implements IPageService
{
    private IPageRepository $pageRepository;

    public function __construct(IPageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    public function createPage(Page $data, string $userId): BaseModel
    {
        try {
            $register = $this->pageRepository->createPage($data, $userId);
            return $register;
        } catch (\Exception $e) {
            throw new \Exception('Error create page : ' . $e->getMessage(), 500);
        }
    }

    public function getPage(): array
    {
        try {
            $register = $this->pageRepository->getPage();
            return $register;
        } catch (\Exception $e) {
            throw new \Exception('Error when getPage : ' . $e->getMessage(), 500);
        }
    }

    public function getPageById(string $slug): ?Page
    {
        try {
            $register = $this->pageRepository->getPageById($slug);
            return $register;
        } catch (\Exception $e) {
            throw new \Exception('Error when getPageById : ' . $e->getMessage(), 500);
        }
    }

}
