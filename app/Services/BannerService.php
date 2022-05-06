<?php

namespace App\Services;

use App\Repositories\BannerRepository;

class BannerService
{
    private $repository;

    public function __construct(BannerRepository $bannerRepository)
    {
        $this->repository = $bannerRepository;
    }

    public function getBanners(array $filter): array
    {
        return $this->repository->getBanners($filter);
    }
}
