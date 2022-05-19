<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\City;
use App\Models\District;
use Storage;

class CityAndDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filename = 'CityCountyData';

        $path = storage_path() . "/app/public" . "/${filename}.json";

        $json = json_decode(file_get_contents($path), true);

        foreach ($json as $county) {
            $city = app(City::class)->create([
                    'id' => uniqid(),
                    'city_name' => $county['CityName'],
                ]);
            foreach ($county['AreaList'] as $district) {
                app(District::class)->create([
                        'id' => uniqid(),
                        'city_id' => $city->id,
                        'district_name' => $district['AreaName'],
                    ]);
            }
        }
    }
}
