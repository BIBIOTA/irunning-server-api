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

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class,'logout']);

Route::prefix('aqi')->group(function () {
    Route::get('/', [AqiController::class, 'getAqiList'])->name('aqi.getAqiList');
});

Route::prefix('activities')->group(function () {
    Route::get('', [ActivityController::class, 'getActivities'])->name('activities.getActivities');
    Route::get('/{runningUuId}', [ActivityController::class, 'getActivity'])
    ->name('activities.getActivity');
});

Route::prefix('cities')->group(function () {
    Route::get('/', [CityController::class, 'getCities'])->name('cities.getCities');
});

Route::prefix('districts')->group(function () {
    Route::get('/', [DistrictController::class, 'getDistricts'])->name('districts.getDistricts');
});

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'getEvents'])->name('events.getEvents');
});

Route::prefix('index')->group(function () {
    Route::get('getIndexEvents', [IndexController::class, 'getIndexEvents'])->name('index.getIndexEvents');
});

// TODO: Remove this route
Route::prefix('image')->group(function () {
    Route::get('getRamdomWeatherImage', [WeatherController::class, 'getRamdomWeatherImage'])->name('image.getRamdomWeatherImage');
});

Route::prefix('member')->group(function () {
    Route::get('/', [MemberController::class, 'read'])->name('member.read');
    Route::put('/', [MemberController::class, 'update'])->name('member.update');
    Route::get('/getIndexRunInfo', [MemberController::class, 'getIndexRunInfo'])
    ->name('member.getIndexRunInfo');
});

Route::prefix('telegram')->group(function () {
    Route::post('/follow', [TelegramUserController::class, 'followEvent'])->name('telegram.follow');
    Route::post('/unfollow', [TelegramUserController::class, 'unfollowEvent'])->name('telegram.unfollow');
    Route::get('/followingEvent', [TelegramUserController::class, 'getFollowingEvent'])->name('telegram.followingEvent');
    Route::post('/subscribe', [TelegramUserController::class, 'subscribe'])->name('telegram.subscribe');
    Route::post('/unsubscribe', [TelegramUserController::class, 'unsubscribe'])->name('subscribe.unsubscribe');
});

Route::prefix('weather')->group(function () {
    Route::get('/', [WeatherController::class, 'getWeather'])->name('weather.getWeather');
});

Route::prefix('banner')->group(function () {
    Route::get('/', [BannerController::class, 'getBanners'])->name('banner.getBanners');
});

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'getNews'])->name('banner.getNews');
});
