<?php

namespace App\Services;

use App\Repositories\DistrictRepository;

class DistrictService
{
    private DistrictRepository $repository;

    /**
     * @param DistrictRepository $repository
     */
    public function __construct(DistrictRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $cityId
     *
     * @return array
     */
    public function getDistricts(string $cityId): array
    {
        return $this->repository->getDistricts($cityId);
    }
}
