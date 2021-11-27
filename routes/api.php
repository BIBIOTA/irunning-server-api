<?php

use App\Http\Controllers\Apis\RequestApi;
use App\Http\Controllers\AqiController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\WeatherController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('login')->group(function(){
    Route::post('login', [LoginController::class, 'login'])->name('login.login');
});

Route::prefix('aqi')->group(function(){
    Route::get('', [AqiController::class, 'getAqiList'])->name('aqi.getAqiList');
});

Route::prefix('activities')->group(function(){
    Route::get('', [ActivityController::class, 'getActivities'])->name('activities.getActivities');
    Route::get('{memberUuid}/{runningUuId}', [ActivityController::class, 'getActivity'])->name('activities.getActivity');
});

Route::prefix('cities')->group(function(){
    Route::get('', [CityController::class, 'getCities'])->name('cities.getCities');
});

Route::prefix('districts')->group(function(){
    Route::get('', [DistrictController::class, 'getDistricts'])->name('districts.getDistricts');
});

Route::prefix('events')->group(function(){
    Route::get('', [EventController::class, 'getEvents'])->name('events.getEvents');
});

Route::prefix('index')->group(function(){
    Route::get('getIndexEvents', [IndexController::class, 'getIndexEvents'])->name('index.getIndexEvents');
});

Route::prefix('member')->group(function(){
    Route::get('getIndexRunInfo', [MemberController::class, 'getIndexRunInfo'])->name('member.getIndexRunInfo');
    Route::post('updateMemberLocation', [MemberController::class, 'updateMemberLocation'])->name('member.updateMemberLocation');
});

Route::prefix('weather')->group(function(){
    Route::get('', [WeatherController::class, 'getWeather'])->name('weather.getWeather');
    Route::get('getWeatherImage', [WeatherController::class, 'getWeatherImage'])->name('weather.getWeatherImage');
});

