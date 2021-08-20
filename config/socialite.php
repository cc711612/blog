<?php
/**
 * @Author: Roy
 * @DateTime: 2021/8/20 上午 10:36
 */
return [
    'facebook' => [
        'client_id'     => env('FACEBOOK_KEY'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect'      => sprintf("%s%s",config('app.url'),env('FACEBOOK_REDIRECT_URI')) ,
    ],
];
