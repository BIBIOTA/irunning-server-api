<?php

namespace App\Services;

use App\Repositories\CityRepository;

class CityService
{
    private CityRepository $cityRepository;

    /**
     * @param CityRepository $cityRepository
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @return array
     */
    public function getCities(): array
    {
        return $this->cityRepository->getCities();
    }
}
