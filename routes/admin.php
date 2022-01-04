<?php

use App\Http\Controllers\AuthController;
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

Route::group([
    'middleware' => 'admin',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});

Route::group([
    'middleware' => 'admin',
    'prefix' => 'members'
], function () {
    Route::get('/', [MemberController::class, 'index'])->name('members.index');
    Route::get('{memberUuid}', [MemberController::class, 'view'])->name('members.view');
    Route::get('{memberUuid}/{runningUuId}', [MemberController::class, 'runningInfo'])->name('members.runningInfo');
});
