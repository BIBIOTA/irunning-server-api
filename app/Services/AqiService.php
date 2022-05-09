<?php

namespace App\Services;

use App\Repositories\AqiRepository;

class AqiService
{
    private AqiRepository $repository;

    public function __construct(AqiRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAqiList(string $cityId): array
    {
        return $this->repository->getAqiList($cityId);
    }
}
