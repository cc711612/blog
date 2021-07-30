<?php

namespace App\Helpers\Metas\Exceptions;

class MetaException
{

    const SERVICE_DOMAIN_FLAG_ERROR = [
        'Code' => 803001,
        'Message' => 'get_service_domain_flag() 沒有回傳任何資訊。',
    ];

    const METADATA_ERROR = [
        'Code' => 803002,
        'Message' => '請檢查是否有建立 config/metadata.php 以及其內容。',
    ];

    const METADATA_CONFIG_ERROR = [
        'Code' => 803003,
        'Message' => '再 config/metadata.php 找不到 %s 的設定值。',
    ];

    const METADATA_METAS_ERROR = [
        'Code' => 803004,
        'Message' => '沒有發現 metas 的設定參數，請檢查 config/metadata.php',
    ];

}
