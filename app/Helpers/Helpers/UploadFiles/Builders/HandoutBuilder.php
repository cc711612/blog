<?php
namespace App\Helpers\UploadFiles\Builders;

use App\Concerns\Databases\Contracts\Constants\Status;
use App\Helpers\UploadFiles\Abstracts\BuilderAbstract;
use App\Helpers\UploadFiles\Services\FileService;


class HandoutBuilder extends BuilderAbstract
{
    // 平台儲存檔案路徑
    protected $center_file_path_format = '/';

    // 客戶儲存檔案路徑
    protected $client_file_path_format = '/';

    // 檔案副檔名
    protected $extension_list = [
        'application/pdf' => 'pdf',
    ];

    /**
     * FileBuilder constructor.
     *
     * @param  \App\Helpers\UploadFiles\Services\FileService  $FileService
     *
     * @Author: Roy
     * @DateTime: 2021/2/17 下午 06:06
     */
    public function __construct(FileService $FileService)
    {

        $this->setService($FileService);
        if (config('app.storage_name') == 's3') {
            $this->center_file_path_format = sprintf('%s%s',config('app.storage_s3_handout_path'), $this->center_file_path_format);
            $this->client_file_path_format = sprintf('%s%s',config('app.storage_s3_handout_path'), $this->client_file_path_format);
        }
    }

    /**
     * [init description]
     * @Author    Boday
     * @DateTime  2018-04-30T13:44:14+0800
     * @param     [type]                    $File       [description]
     * @return    [type]                                [description]
     */
    public function init($File,$Parameter)
    {
        $this->setFile($File);
        $this->setParameter($Parameter);
        $this->setFileName($this->Parameter->vimeo_id);
        return $this;
    }

    /**
     * [genCenterFilePath description]
     * @Author    Boday
     * @DateTime  2018-04-30T15:28:08+0800
     * @return    [type]                    [description]
     */
    protected function genCenterFilePath()
    {

        $file_path = $this->getCenterFilePathFormat();

//        $file_path = str_replace('{vimeo_id}', $this->Parameter->vimeo_id, $file_path);

        return $file_path;
    }

    /**
     * [genClientFilePath description]
     * @Author    Boday
     * @DateTime  2018-04-30T15:33:31+0800
     * @return    [type]                    [description]
     */
    protected function genClientFilePath()
    {
        $file_path = $this->getClientFilePathFormat();

//        $file_path = str_replace('{vimeo_id}', $this->Parameter->vimeo_id, $file_path);

        return $file_path;
    }

    /**
     * [getImageSizeFormat description]
     * @Author    Boday
     * @DateTime  2018-04-30T16:41:29+0800
     * @return    [type]                    [description]
     */
    private function getImageSizeFormat()
    {
        return $this;
    }

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-05-14 17:20
     */
    protected function genUpdateData() : array
    {
//        $image_size_format = $this->getImageSizeFormat();
        return [
            'url' =>  $this->getFileURL(),
            'title' => $this->getFile()->getClientOriginalName(),
            'mime_type' => $this->getFile()->getMimeType(),
            'size' => $this->getFile()->getSize(),
            'filename' => $this->getFileName(),
            'extension' => $this->getExtension(),
//            'width' => $image_size_format['width'],
//            'height' => $image_size_format['height'],
            'status' => Status::STATUS_ENABLE,
        ];
    }

}
