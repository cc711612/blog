<?php

namespace App\Http\Requesters\Api;

use App\Concerns\Databases\Contracts\Constants\Status;
use App\Modules\Identifications\AccountTypes\Contracts\Constants\AccountType;
use App\Concerns\Databases\Request;
use Arr;

class ImageStoreRequester extends Request
{
    protected function schema(): array
    {
        return [
            'size' => 0,
            'mime_type' => '',
            'file_name' => ''
        ];
    }

    protected function map($row): array
    {
        return [
            'size' => $row->file('uploadedFile')->getSize(),
            'mime_type' => $row->file('uploadedFile')->getMimeType(),
            'file_name' => $row->file('uploadedFile')->getClientOriginalName()
        ];
    }

}
