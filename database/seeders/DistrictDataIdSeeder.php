<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\City;
use App\Models\District;

class DistrictDataIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
                        
            $datas = app(City::class)::select('id', 'dataid', 'city_name')->whereNotNull('dataid')->get();

            foreach($datas as $city) {
                $districts = app(District::class)->where('city_id', $city->id)->get();
                foreach($districts as $district) {
                    $response = Http::get('https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-093?Authorization=CWB-12E073F0-06A2-4F1E-BEB7-7FB421E605A2'.'&'.'locationId'.'='.$city->dataid.'&'.'locationName'.'='.$district->district_name);
                    $resdatas = $response->json();
                    if (count($resdatas) > 0) {
                        foreach($resdatas['records']['locations'] as $data) {
                            $district->dataid = $data['dataid'];
                            $district->save();
                        }
                    } else {
                        Log::info('無法取得資料');
                    }
    
                }
            }
            Log::info('鄉鎮區天氣資訊dataid更新完成');
        } catch (Throwable $e) {
            Log::info($e);
        }
    }
}
