<?php

namespace App\Http\Controllers\Api\Bots;

use App\Jobs\replyMessage;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;


class LineController extends BaseController
{

    public function reply(Request $request)
    {

        Log::channel()->info(json_encode($request->toArray(), JSON_UNESCAPED_UNICODE));
//        $json_data = '{"destination":"U9271924bac0bcd304cd25f15530cc22d","events":[{"type":"message","message":{"type":"text","id":"15719994512281","text":"123123"},"timestamp":1646900701059,"source":{"type":"user","userId":"U1d40789aa8461e74ead62181b1abc442"},"replyToken":"3d9d8f48f9c34b2ca447f3818407f595","mode":"active"}]}';
//        $result = $this->jsonToArray($json_data);
//        $result = $request;
        try {
            if (is_null(Arr::get($request, 'events')) == false && empty(Arr::get($request, 'events')) == false) {
                //實體化linebot物件
                //取得使用者id和訊息內容
                $user_content = Arr::get($request, 'events.0.message.text');
                $user_id = Arr::get($request, 'events.0.source.userId');
                $reply_token = Arr::get($request, 'events.0.replyToken');
                replyMessage::dispatch(
                    [
                        'user_id' => $user_id,
                        'reply_token' => $reply_token,
                        'user_content' => $user_content,
                        'events' => Arr::get($request, 'events'),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::channel()->error(json_encode($e, JSON_UNESCAPED_UNICODE));
        }

        return response('', 200);
    }


}
