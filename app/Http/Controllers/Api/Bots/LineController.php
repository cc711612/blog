<?php

namespace App\Http\Controllers\Api\Bots;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;


class LineController extends BaseController
{

    public function test(Request $request){
        Log::channel()->info(json_encode($request->toArray(), JSON_UNESCAPED_UNICODE));
        //實體化linebot物件
        $httpClient = new CurlHTTPClient(env('LINEBOT_TOKEN'));
        $bot = new LINEBot($httpClient, ['channelSecret' => env('LINEBOT_SECRET')]);

        //取得使用者id和訊息內容
        $text = $request->events[0]['message']['text'];
        $user_id = $request->events[0]['source']['userId'];

        //透過dialogFlow判斷訊息意圖
        $dialog = $this->dialog($text);
        $noLimit = !strpos($dialog->content(), 'Vague response');

        //將以上拿到的資訊寫進log裡，debug用
        Log::info($text);
        Log::debug($dialog->content());
        return response('',200);
    }
}
