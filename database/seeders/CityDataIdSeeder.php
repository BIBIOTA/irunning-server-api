<?php

namespace Database\Seeders;

use App\Models\City;

use Illuminate\Database\Seeder;


class CityDataIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            '宜蘭縣' => 'F-D0047-001',
            '桃園市' => 'F-D0047-005',
            '新竹縣' => 'F-D0047-009',
            '苗栗縣' => 'F-D0047-013',
            '彰化縣' => 'F-D0047-017',
            '南投縣' => 'F-D0047-021',
            '雲林縣' => 'F-D0047-025',
            '嘉義縣' => 'F-D0047-029',
            '屏東縣' => 'F-D0047-033',
            '臺東縣' => 'F-D0047-037',
            '花蓮縣' => 'F-D0047-041',
            '澎湖縣' => 'F-D0047-045',
            '基隆市' => 'F-D0047-049',
            '新竹市' => 'F-D0047-053',
            '嘉義市' => 'F-D0047-057',
            '臺北市' => 'F-D0047-061',
            '高雄市' => 'F-D0047-065',
            '新北市' => 'F-D0047-069',
            '臺中市' => 'F-D0047-001',
            '臺中市' => 'F-D0047-073',
            '臺南市' => 'F-D0047-077',
            '連江縣' => 'F-D0047-081',
            '金門縣' => 'F-D0047-085',
        ];

        foreach($cities as $key => $value) {
            $data = app(City::class)->where('CityName', $key)->update([
                'dataid' => $value,
            ]);
        }
    }
}
