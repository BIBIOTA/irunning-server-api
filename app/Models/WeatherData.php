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

    public function getData($weatherDetailId, $districtId)
    {
        $query = $this->newModelQuery();

        $query->where('weather_datas.weather_document_id', $weatherDetailId);

        $query->where('weather_datas.district_id', $districtId);

        $query->where('weather_datas.start_time', '>=', Carbon::now());

        $query->orderBy('weather_datas.start_time', 'ASC');

        $weatherData = $query->first();

        return $weatherData;
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function weatherDocument()
    {
        return $this->belongsTo(WeatherDocument::class);
    }

    public function wxDocument()
    {
        return $this->belongsTo(WxDocument::class, 'value', 'value');
    }
}
