<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Traits\Running;

use Illuminate\Support\Facades\Http;

use App\Models\Aqi;
use App\Models\Activity;
use App\Models\City;
use App\Models\District;
use App\Models\Event;
use App\Models\EventDistance;
use App\Models\Member;
use App\Models\MemberToken;
use App\Models\Weather;
use App\Models\Stat;

use App\Http\Controllers\Traits\StravaActivitiesTrait;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RequestApi extends Controller
{
    use Running;
    use StravaActivitiesTrait;


}
