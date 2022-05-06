<?php

namespace App\Services;

use App\Repositories\NewsRepository;

class NewsService
{
    private $repository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->repository = $newsRepository;
    }
    
    public function getNews(array $filter): array
    {
        return $this->repository->getNews($filter);
    }
}
