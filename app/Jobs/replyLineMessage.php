<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use App\Models\Supports\SocialType;
use App\Models\Services\SocialService;

class replyLineMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array|\ArrayAccess|mixed
     */
    private $user_id;
    /**
     * @var array|\ArrayAccess|mixed
     */
    private $reply_token;
    /**
     * @var array|\ArrayAccess|mixed
     */
    private $user_content;
    /**
     * @var array|\ArrayAccess|mixed
     */
    private $events;
    /**
     * @var \App\Models\Services\SocialService
     */
    private $social;

    /**
     * replyLineMessage constructor.
     *
     * @param $params
     *
     * @Author: Roy
     * @DateTime: 2022/3/16 上午 11:48
     */
    public function __construct($params)
    {
        //
        $this
            ->onQueue('reply_message');
        $this->user_id = Arr::get($params, 'user_id');
        $this->reply_token = Arr::get($params, 'reply_token');
        $this->user_content = Arr::get($params, 'user_content');
        $this->events = Arr::get($params, 'events');
        $this->social = (new SocialService());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //實體化linebot物件
            $httpClient = new CurlHTTPClient(config('bot.line.access_token'));
            $bot = new LINEBot($httpClient, ['channelSecret' => config('bot.line.channel_secret')]);

            $user_info = $this->jsonToArray($bot->getProfile($this->user_id)->getRawBody());
            /**
             * "userId" => "U1d40789aa8461e74ead62181b1abc442"
             * "displayName" => "冠融"
             * "pictureUrl" => "https://sprofile.line-scdn.net/0hE0DiZsyUGh9pEDa-y8xkYBlAGXVKYUMNQ3YCLFtCTXtRKQ8cTCVQfg4QTSkAIg0aEHZcKVsZFy1lA215d0bmK24gRChQJ1VNQ35T_g"
             * "language" => "zh-TW"
             */
            # 除了文字
            if (is_null($this->user_content) == true) {
                $this->user_content = "目前不支援文字之外的輸入";
            }
            # 傳送訊息
            $bot->replyText($this->reply_token, sprintf("%s 您好 :\n%s", Arr::get($user_info, 'displayName'), $this->user_content));
            Log::channel('bot')->info(sprintf("%s Reply SUCCESS params : %s", get_class($this),json_encode($this->events, JSON_UNESCAPED_UNICODE)));
            # 更新Social資料
            $data = [
                'social_type_value' => $this->user_id,
                'social_type'       => SocialType::Line,
            ];
            Arr::set($data, 'name', Arr::get($user_info, 'displayName'));
            Arr::set($data, 'image', Arr::get($user_info, 'pictureUrl'));
            $this->social->setRequest($data)->updateOrCreate();
            Log::channel('bot')->info(sprintf("%s UpdateSocial SUCCESS params : %s", get_class($this),json_encode($data, JSON_UNESCAPED_UNICODE)));
        } catch (\Exception $exception) {
            Log::channel('bot')->info(sprintf("%s Error params : %s", get_class($this),
                json_encode($exception, JSON_UNESCAPED_UNICODE)));
        }
    }

    /**
     * @param string $json
     * @return mixed
     */
    private function jsonToArray(string $json)
    {
        return json_decode($json, 1);
    }
}
