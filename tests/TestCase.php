<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function distinctCities () {
        return ['釣魚臺', '南海島'];
    }

    public function distinctDistricts () {
        return ['釣魚臺', '東沙群島','南沙群島'];
    }

    public function getCityCountyData() {
        $filename = 'CityCountyData';

        $path = storage_path() . "/app/public" ."/${filename}.json";

        $json = json_decode(file_get_contents($path), true);

        return $json;
    }
}
