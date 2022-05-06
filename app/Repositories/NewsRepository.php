<?php
namespace App\Repositories;

use App\Models\News;

class NewsRepository
{
    private $model;

    public function __construct(News $news)
    {
        $this->model = $news;
    }

    public function getNews(array $filter): array
    {
        return $this
                ->model
                ->where('is_active', true)
                ->limit($filter['limit'] ?? null)
                ->get()
                ->toArray();
    }
}
