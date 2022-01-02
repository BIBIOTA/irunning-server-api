<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeatherData extends Model
{
    use HasFactory;

    protected $table = 'weather_datas';

    protected $fillable = ['id'];

    public $incrementing = false;

    public function getDatas($districtId, $datetime) {
        $query = $this->newModelQuery();

        $query->where('weather_datas.district_id', $districtId);

        //TODO 取得天氣資訊時間調整演算
        $query->where('weather_datas.end_time', '<=', Carbon::now()->addhour(7));

        $query->where('weather_datas.start_time', '>=', Carbon::now());

        $weatherDatas = $query->get();

        return $this->dataProcess($weatherDatas, $districtId);

    }

    private function dataProcess ($weatherDatas, $districtId) {
        
        $data = [];
        
        if ($weatherDatas->count() > 0) {


            foreach($weatherDatas as $weatherData) {
                $weatherDocument = $weatherData->weatherDocument;
                $name = $weatherDocument->name;
                if ($name === 'Wx') {
                    $wxDocuments = app(WxDocument::class)->where('value', $weatherData->value)->first();
                    $data['WxValue'] = $wxDocuments ? $wxDocuments->text : null;
                }
                $data[$name] = $weatherData->value;
                $data['start_time'] = $weatherData->start_time;
                $data['end_time'] = $weatherData->end_time;
            }

            //FIXME 取得六小時天氣資訊修復
            if (empty($data['PoP6h'])) {
                $data['PoP6h'] = $this->getFirstPop6h($districtId);
            }

        }
        return $data;
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function weatherDocument () {
        return $this->belongsTo(WeatherDocument::class);
    }

    public function wxDocument() {
        return $this->belongsTo(WxDocument::class, 'value');
    }

    public function getFirstPop6h($districtId) {
        $query = $this->newModelQuery();

        $query->select('weather_datas.value');

        $query->join('weather_documents', 'weather_documents.id', '=', 'weather_datas.weather_document_id');

        $query->where('weather_datas.district_id', $districtId);

        $query->where('weather_documents.name', 'PoP6h');

        $weatherData = $query->first();

        if ($weatherData) {
            return $weatherData->value;
        }

        return 0;

    }
}
