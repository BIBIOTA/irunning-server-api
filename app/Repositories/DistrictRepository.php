<?php
namespace App\Repositories;

use App\Models\District;

class DistrictRepository
{
    private District $model;

    /**
     * @param District $model
     */
    public function __construct(District $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $cityId
     *
     * @return array
     */
    public function getDistricts(string $cityId): array
    {
        return $this->model->where('city_id', $cityId)->get()->toArray();
    }
}
