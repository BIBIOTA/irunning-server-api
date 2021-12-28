<?php

namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait WeatherTrait
{
  public function getHttpWeatherData($cityId, $district) {
    $response = Http::get('https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-093?Authorization=CWB-12E073F0-06A2-4F1E-BEB7-7FB421E605A2'.'&'.'locationId'.'='.$cityId.'&'.'locationName'.'='.$district);
    return $response->json();
  }
}
