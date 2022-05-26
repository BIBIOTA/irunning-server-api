<?php

namespace App\Http\Responses;

class Message
{
    const SUCCESS = 'ok';
    const NOTFOUND = 'not found';
    const SERVERERROR = 'internal server error';
    const LOGINFAILED = 'login failed';
    const DATAEXISTS = 'data exists';
}
