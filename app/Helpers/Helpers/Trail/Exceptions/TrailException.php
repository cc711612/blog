<?php

namespace App\Helpers\Trail\Exceptions;

class TrailException
{

    const SERVICE_DOMAIN_FLAG_ERROR = [
        'Code' => 804001,
        'Message' => 'get_service_domain_flag() 沒有回傳任何資訊。',
    ];

    const METADATA_ERROR = [
        'Code' => 804002,
        'Message' => '請檢查是否有建立 config/metadata.php 以及其內容。',
    ];

    const METADATA_APP_ROUTE_ERROR = [
        'Code' => 804003,
        'Message' => '再 config/metadata.php 找不到 %s 的設定值。',
    ];

    const METADATA_METAS_ERROR = [
        'Code' => 804004,
        'Message' => '沒有發現 title 的設定參數，請檢查 config/metadata.php',
    ];

    const ARRAY_KEY_NOT_EXISTS = [
        'Code' => 804005,
        'Message' => '您帶入的陣列鍵必須是 text + href 的組合。 ([\'text\' => xxxx,\'href\' => xxxx ])',
    ];

    const NO_ARGUMENT = [
        'Code' => 804006,
        'Message' => '很抱歉，目前沒有發現您有帶任何的參數喔。',
    ];

}
