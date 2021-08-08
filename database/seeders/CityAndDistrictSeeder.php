<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(City::class)->truncate();
        app(District::class)->truncate();

        $filename = 'CityCountyData';

        $path = storage_path() . "/app/public" ."/${filename}.json";

        $json = json_decode(file_get_contents($path), true); 
        logger($json);

        foreach($json as $county) {
            app(City::class)->create(['CityName' => $county['CityName']]);
            foreach($county['AreaList'] as $district) {
                app(District::class)->create(['CityName' => $county['CityName'], 'AreaName' => $district['AreaName']]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
