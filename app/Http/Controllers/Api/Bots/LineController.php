<?php

namespace App\Http\Controllers\Api\Bots;

use App\Jobs\replyLineMessage;
use App\Jobs\sendLineMessage;
use App\Jobs\socialUpdate;
use App\Models\Services\SocialService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;


class LineController extends BaseController
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @Author: Roy
     * @DateTime: 2022/3/15 上午 11:28
     */
    public function reply(Request $request)
    {

        Log::channel()->info(json_encode($request->toArray(), JSON_UNESCAPED_UNICODE));

        try {
            if (is_null(Arr::get($request, 'events')) == false && empty(Arr::get($request, 'events')) == false) {
                $user_id = Arr::get($request, 'events.0.source.userId');
                # reply
                if (Arr::get($request, 'events.0.type') == 'message') {
                    $user_content = Arr::get($request, 'events.0.message.text');
                    $reply_token = Arr::get($request, 'events.0.replyToken');
                    replyLineMessage::dispatch(
                        [
                            'user_id' => $user_id,
                            'reply_token' => $reply_token,
                            'user_content' => $user_content,
                            'events' => Arr::get($request, 'events'),
                        ]
                    );
                } else {
                    socialUpdate::dispatch(
                        [
                            'user_id' => $user_id,
                            'events' => Arr::get($request, 'events'),
                        ]
                    );
                }
                # follow
                Log::channel()->info(Arr::get($request, 'events.0.type'));
            }
        } catch (\Exception $e) {
            Log::channel()->error(json_encode($e, JSON_UNESCAPED_UNICODE));
        }

        return response('', 200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @Author: Roy
     * @DateTime: 2022/3/15 上午 11:28
     */
    public function send(Request $request)
    {

        sendLineMessage::dispatch(
            [
                'user_id' => "U1d40789aa8461e74ead62181b1abc442",
                'message' => sprintf("此文章有新留言~ %s", route("article.show", ['article' => 53])),
            ]
        );
        return response('', 200);
    }

    /**
     * @return \LINE\LINEBot\HTTPClient\CurlHTTPClient
     * @Author: Roy
     * @DateTime: 2022/3/15 上午 11:28
     */
    private function getHttpClient()
    {
        return (new CurlHTTPClient(config('bot.line.access_token')));
    }

    /**
     * @return \LINE\LINEBot
     * @Author: Roy
     * @DateTime: 2022/3/15 上午 11:28
     */
    private function getLineBot()
    {
        return (new LINEBot($this->getHttpClient(), ['channelSecret' => config('bot.line.channel_secret')]));
    }

    /**
     * @param string $json
     *
     * @return mixed
     * @Author: Roy
     * @DateTime: 2022/3/15 上午 11:28
     */
    private function jsonToArray(string $json)
    {
        return json_decode($json, 1);
    }

}
