<?php

namespace App\Helpers\UploadFiles\Exceptions;

class UploadFileException
{

    const TYPE_ERROR = [
        'Code' => 807001,
        'Message' => '很抱歉!! 檔案格式目前僅支援 %s 的檔案格式!!',
    ];

    const GET_BUILDER_ERROR = [
        'Code' => 807002,
        'Message' => '很抱歉!! 您指定的生成主不存在!!',
    ];

    const CREATE_DRAFT = [
        'Code' => 807003,
        'Message' => '很抱歉!! 您尚未建立草稿喔!!',
    ];

    const UPDATE_ID_ERROR = [
        'Code' => 807004,
        'Message' => '很抱歉!! 你要更新的圖檔ID有誤!!',
    ];

    const UPDATE_DATA_ERROR = [
        'Code' => 807005,
        'Message' => '很抱歉!! 你要更新的資料有誤!!',
    ];

    const IMAGE_MIME_TYPE_ERROR = [
        'Code' => 807011,
        'Message' => '很抱歉!! 你上傳的圖檔格式[MimeType]有誤!!',
    ];
}
