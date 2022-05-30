<?php

use App\Http\Controllers\Apis\RequestApi;
use App\Http\Controllers\AqiController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TelegramUserController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\WeatherController;
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

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::prefix('aqi')->controller(AqiController::class)->group(function () {
    Route::get('/', 'getAqiList')->name('aqi.getAqiList');
});

Route::prefix('activities')->controller(ActivityController::class)->group(function () {
    Route::get('', 'getActivities')->name('activities.getActivities');
    Route::get('/{runningUuId}', 'getActivity')
    ->name('activities.getActivity');
});

Route::prefix('cities')->controller(CityController::class)->group(function () {
    Route::get('/', 'getCities')->name('cities.getCities');
});

Route::prefix('districts')->controller(DistrictController::class)->group(function () {
    Route::get('/', 'getDistricts')->name('districts.getDistricts');
});

Route::prefix('events')->controller(EventController::class)->group(function () {
    Route::get('/', 'getEvents')->name('events.getEvents');
});

Route::prefix('index')->controller(IndexController::class)->group(function () {
    Route::get('getIndexEvents', 'getIndexEvents')->name('index.getIndexEvents');
});

// TODO: Remove this route
Route::prefix('image')->controller(WeatherController::class)->group(function () {
    Route::get('getRamdomWeatherImage', 'getRamdomWeatherImage')->name('image.getRamdomWeatherImage');
});

Route::prefix('member')->controller(MemberController::class)->group(function () {
    Route::get('/', 'read')->name('member.read');
    Route::put('/', 'update')->name('member.update');
    Route::get('/getIndexRunInfo', 'getIndexRunInfo')
    ->name('member.getIndexRunInfo');
});

Route::prefix('telegram')->controller(TelegramUserController::class)->group(function () {
    Route::post('/follow', 'followEvent')->name('telegram.follow');
    Route::post('/unfollow', 'unfollowEvent')->name('telegram.unfollow');
    Route::get('/followingEvent', 'getFollowingEvent')->name('telegram.followingEvent');
    Route::post('/subscribe', 'subscribe')->name('telegram.subscribe');
    Route::post('/unsubscribe', 'unsubscribe')->name('subscribe.unsubscribe');
});

Route::prefix('weather')->controller(WeatherController::class)->group(function () {
    Route::get('/', 'getWeather')->name('weather.getWeather');
});

Route::prefix('banner')->controller(BannerController::class)->group(function () {
    Route::get('/', 'getBanners')->name('banner.getBanners');
});

Route::prefix('news')->controller(NewsController::class)->group(function () {
    Route::get('/', 'getNews')->name('banner.getNews');
});
