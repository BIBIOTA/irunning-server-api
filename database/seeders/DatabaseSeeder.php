<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CityAndDistrictSeeder::class);
        $this->call(CityDataIdSeeder::class);
        $this->call(AqiSeeder::class);
        $this->call(WxDocumentSeeder::class);
        $this->call(WeatherSeeder::class);
    }
}
