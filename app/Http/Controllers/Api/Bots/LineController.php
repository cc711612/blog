<?php

namespace App\Http\Controllers\Api\Bots;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class LineController extends BaseController
{

    public function test(Request $request){
        Log::channel()->info(json_encode($request->toArray(), JSON_UNESCAPED_UNICODE));

        return response('',200);
    }
}
