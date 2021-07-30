<?php

namespace App\Helpers\HttpUri\Exceptions;

class HttpUriException
{

    const CONFIG_EROOR = [
        'Code' => 808001,
        'Message' => '請檢查您的 config/http_uri.php 檔案是否有建立。',
    ];

    const CONFIG_ROUTE_NAME_EROOR = [
        'Code' => 808002,
        'Message' => '請檢查您的 config/http_uri.php 檔案，是否有設定對應 config/route.php 對等的第一維參數',
    ];

    const CONFIG_DOMAIN_NAME_EROOR = [
        'Code' => 808003,
        'Message' => '請檢查您的 config(\'http_uri\') 沒有建立 domain_name 參數值。',
    ];

    const FILESYSTEMS_CONFIG_DISK_EROOR = [
        'Code' => 808004,
        'Message' => '請檢查您的 config/filesystems.php 裡面的 disk 是否有建立了 http_public 項目',
    ];

    const FILE_DOES_NOT_EXIST = [
        'Code' => 808004,
        'Message' => '檔案： %s 不存在!!',
    ];

}
