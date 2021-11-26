<?php

use App\Http\Controllers\Apis\RequestApi;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;

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
    Route::get('import', [RequestApi::class, 'import'])->name('aqi.import');
    Route::get('getAqiList', [RequestApi::class, 'getAqiList'])->name('aqi.getAqiList');
});

Route::prefix('activities')->group(function(){
    Route::get('/', [RequestApi::class, 'getActivities'])->name('activities.getActivities');
    Route::get('{memberUuid}/{runningUuId}', [RequestApi::class, 'getActivity'])->name('activities.getActivity');
});

Route::prefix('cities')->group(function(){
    Route::get('getCities', [RequestApi::class, 'getCities'])->name('cities.getCities');
    Route::get('getDistricts', [RequestApi::class, 'getDistricts'])->name('districts.getDistricts');
});

Route::prefix('events')->group(function(){
    Route::get('/', [RequestApi::class, 'getEvents'])->name('events.getEvents');
});

Route::prefix('index')->group(function(){
    Route::get('getIndexEvents', [RequestApi::class, 'getIndexEvents'])->name('index.getIndexEvents');
});

Route::prefix('member')->group(function(){
    Route::get('index', [RequestApi::class, 'getIndexRunInfo'])->name('member.getIndexRunInfo');
    Route::post('updateMemberLocation', [RequestApi::class, 'updateMemberLocation'])->name('member.updateMemberLocation');
});

Route::prefix('weather')->group(function(){
    Route::get('getWeather', [RequestApi::class, 'getWeather'])->name('weather.getWeather');
});

