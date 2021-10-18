<?php

namespace App\Http\Validators\Api;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use App\Concerns\Databases\Contracts\Request;

class ImageStoreValidator extends ValidatorAbstracts
{
    private $image_size = 3000;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function rules(): array
    {
        return [
            'size' => [
                'required',
                sprintf('max:%s',$this->image_size)
            ],
            'mime_type' => [
                'required',
                'in:image/jpg,image/jpeg,image/png,image/gif'
            ],
            'file_name' => [
                'required'
            ]
        ];
    }

    protected function messages(): array
    {
        return [
            'size.required' => '上傳失敗',
            'size.max' => '上傳的檔案不能超過 :max kb',
            'mime_type.required' => '錯誤的檔案格式',
            'mime_type.in' => '錯誤的檔案格式',
            'file_name.required' => '上傳失敗'
        ];
    }
}
