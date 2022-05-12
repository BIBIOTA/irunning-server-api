<?php
namespace App\Repositories;

use App\Models\City;

class CityRepository
{
    private City $model;

    /**
     * @param City $city
     */
    public function __construct(City $city)
    {
        $this->model = $city;
    }

    /**
     * @return array
     */
    public function getCities(): array
    {
        return $this->model->whereNotNull('dataid')->get()->toArray();
    }
}
