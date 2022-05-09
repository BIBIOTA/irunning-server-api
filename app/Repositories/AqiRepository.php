<?php
namespace App\Repositories;

use App\Models\Aqi;

class AqiRepository
{
    private Aqi $model;

    public function __construct(Aqi $model)
    {
        $this->model = $model;
    }

    public function getAqiList(string $cityId): array
    {
        return $this->model->where('city_id', $cityId)->get()->toArray();
    }
}
