<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\City;
use App\Models\District;
use App\Models\Aqi;

class TruncateCityAndDistrictAndAqiSeeder extends Seeder
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
        app(Aqi::class)->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
