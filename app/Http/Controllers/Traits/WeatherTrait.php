<?php

namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait WeatherTrait
{
    public function getHttpWeatherData($cityId, $district)
    {
        $response = Http::get('https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-093?Authorization=CWB-12E073F0-06A2-4F1E-BEB7-7FB421E605A2' . '&' . 'locationId' . '=' . $cityId . '&' . 'locationName' . '=' . $district);
        return $response->json();
    }

    public function getColumnsKey($elementName)
    {
        $datas = ['T', 'AT', 'PoP6h', 'CI', 'Wx'];

        return in_array($elementName, $datas);
    }

    public function getValue($elementName, $elementValue)
    {
        $result;
        switch ($elementName) {
            case 'T':
                return $elementValue[0]['value'];
                break;
            case 'AT':
                return $elementValue[0]['value'];
                break;
            case 'PoP6h':
                return $elementValue[0]['value'];
                break;
            case 'CI':
                return $elementValue[1]['value'];
                break;
            case 'Wx':
                return $elementValue[1]['value'];
                break;
            default:
                return '';
                break;
        }
    }
}
