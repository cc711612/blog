<?php

namespace App\Helpers\Htmls\Exceptions;

class HtmlException
{

    const SERVICE_DOMAIN_FLAG_ERROR = [
        'Code' => 806001,
        'Message' => 'get_service_domain_flag() 沒有回傳任何資訊。',
    ];

    const METADATA_ERROR = [
        'Code' => 806002,
        'Message' => '請檢查是否有建立 config/metadata.php 以及其內容。',
    ];

    const METADATA_METAS_ERROR = [
        'Code' => 806004,
        'Message' => '沒有發現 links and scripts 的設定參數，請檢查 config/metadata.php',
    ];

    const METHOD_NOT_EXISTS = [
        'Code' => 806005,
        'Message' => '您目前呼叫的方法不存在，請查閱手冊再繼續執行!!',
    ];

    const ATTRIBUTE_NOT_ARRAY = [
        'Code' => 806006,
        'Message' => '第二個參數必須是 array 的格式!!',
    ];

    const ATTRIBUTE_NOT_ENOUGH = [
        'Code' => 806107,
        'Message' => '缺少第二個參數!!',
    ];

}
