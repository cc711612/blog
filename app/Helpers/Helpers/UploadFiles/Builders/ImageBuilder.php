<?php

namespace App\Helpers\UploadFiles\Builders;

use App\Concerns\Databases\Contracts\Constants\Status;
use App\Helpers\UploadFiles\Abstracts\BuilderAbstract;
use App\Helpers\UploadFiles\Services\ImageService;

class ImageBuilder extends BuilderAbstract
{

    // 平台儲存檔案路徑
    protected $center_file_path_format = '/center/images/{date_Ym}/{date_d}/{image_id}';

    // 客戶儲存檔案路徑
    protected $client_file_path_format = '/client/images/{date_Ym}/{date_d}/{image_id}';

    // 檔案副檔名
    protected $extension_list = [
        'image/jpg'  => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/jpeg' => 'jpg',
    ];

    /**
     * [__construct description]
     *
     * @Author    Boday
     * @DateTime  2018-04-30T13:44:51+0800
     *
     * @param  ImageService  $ImageService  [description]
     */
    public function __construct(ImageService $ImageService)
    {
        $this->setService($ImageService);

        if (config('app.storage_name') == 's3') {
            $this->center_file_path_format = sprintf('%s%s', config('app.storage_s3_path'),
                $this->center_file_path_format);
            $this->client_file_path_format = sprintf('%s%s', config('app.storage_s3_path'),
                $this->client_file_path_format);
        }
    }

    /**
     * @param $File
     * @param $Parameter
     *
     * @return $this
     * @Author: Roy
     * @DateTime: 2021/2/19 下午 02:12
     */
    public function init($File,$Parameter)
    {
        $this->setFile($File);
        $this->setParameter($Parameter);
        return $this;
    }

    /**
     * [genCenterFilePath description]
     *
     * @Author    Boday
     * @DateTime  2018-04-30T15:28:08+0800
     * @return    [type]                    [description]
     */
    protected function genCenterFilePath()
    {

        $file_path = $this->getCenterFilePathFormat();

        $file_path = str_replace('{date_Ym}', date('Ym'), $file_path);
        $file_path = str_replace('{date_d}', date('d'), $file_path);
        $file_path = str_replace('{image_id}', $this->Entity->id, $file_path);

        return $file_path;
    }

    /**
     * [genClientFilePath description]
     *
     * @Author    Boday
     * @DateTime  2018-04-30T15:33:31+0800
     * @return    [type]                    [description]
     */
    protected function genClientFilePath()
    {
        $file_path = $this->getClientFilePathFormat();

        $file_path = str_replace('{date_Ym}', date('Ym'), $file_path);
        $file_path = str_replace('{date_d}', date('d'), $file_path);
        $file_path = str_replace('{image_id}', $this->Entity->id, $file_path);

        return $file_path;
    }

    /**
     * [getImageSizeFormat description]
     *
     * @Author    Boday
     * @DateTime  2018-04-30T16:41:29+0800
     * @return    [type]                    [description]
     */
    private function getImageSizeFormat()
    {
        list($width, $height) = getimagesize($this->getFile()->getRealPath());
        return compact('width', 'height');
    }

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-05-14 17:20
     */
    protected function genUpdateData(): array
    {
        $image_size_format = $this->getImageSizeFormat();

        return [
            'url'       => $this->getFileURL(),
            'title'     => $this->getFile()->getClientOriginalName(),
            'mime_type' => $this->getFile()->getMimeType(),
            'size'      => $this->getFile()->getSize(),
            'filename'  => $this->getFileName(),
            'extension' => $this->getExtension(),
            'width'     => $image_size_format['width'],
            'height'    => $image_size_format['height'],
            'status'    => Status::STATUS_ENABLE,
        ];
    }

}
