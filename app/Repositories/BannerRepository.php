<?php
namespace App\Repositories;

use App\Models\Banner;

class BannerRepository
{
    private $model;

    public function __construct(Banner $banner)
    {
        $this->model = $banner;
    }

    public function getBanners(array $filter): array
    {
        return $this
                ->model
                ->where('is_active', true)
                ->limit($filter['limit'] ?? null)
                ->get()
                ->toArray();
    }
}
