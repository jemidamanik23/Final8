<?php


namespace App\Services;

use App\Http\Repositories\PageRepository;

class PageService
{
    protected $pageRepository;

    /**
     * PageService constructor.
     * @param PageRepository $pageRepository
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getPages()
    {
        $pages = $this->pageRepository->getAll()->reject(function ($page) {
            $result = $page->configuration ? $page->configuration->config['display'] : 'false';
            return $result == 'false';
        })->sortBy(function ($page, $key) {
            $result = $page->configuration ? $page->configuration->config['sort_order'] : 1;
            return -$result;
        });
        return $pages;
    }

    public function getPage($name)
    {
        return $this->pageRepository->get($name);
    }
}