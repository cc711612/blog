<?php
namespace App\Helpers\UploadFiles;

use App\Helpers\UploadFiles\Builders\ImageBuilder;
use App\Helpers\UploadFiles\Builders\AudioBuilder;
use App\Helpers\UploadFiles\Directors\Director;
use App\Helpers\UploadFiles\Exceptions\UploadFileException;
use App\Helpers\UploadFiles\Builders\HandoutBuilder;

class UploadFileManager
{

    private $file_format_list = ['image', 'file', 'video','audio','handout'];

    private $client_id;
    private $file_format;
    private $Files;
    private $Director;
    private $builder_list;
    private $Parameter;

    /**
     * UploadFileManager constructor.
     *
     * @param  \App\Helpers\UploadFiles\Directors\Director  $Director
     * @param  \App\Helpers\UploadFiles\Builders\ImageBuilder  $ImageBuilder
     * @param  \App\Helpers\UploadFiles\Builders\AudioBuilder  $AudioBuilder
     *
     * @Author: Roy
     * @DateTime: 2021/2/18 上午 11:49
     */
    public function __construct(Director $Director, ImageBuilder $ImageBuilder , AudioBuilder $AudioBuilder,HandoutBuilder $HandoutBuilder)
    {

        $this->Director = $Director;

        $this->builder_list = [
            'image' => $ImageBuilder,
            'audio' => $AudioBuilder,
            'handout' => $HandoutBuilder,
        ];
    }

    /**
     * [init description]
     * @Author    Boday
     * @DateTime  2018-04-26T15:50:32+0800
     * @return    [type]                    [description]
     */
    public function init(string $file_format)
    {
        $this->setFormat($file_format);
        return $this;
    }

    /**
     * [getBuilder description]
     * @Author    Boday
     * @DateTime  2018-04-30T11:03:35+0800
     * @return    [object]                    [Builder]
     */
    public function getBuilder()
    {

        $Builder = $this->builder_list[$this->getFormat()];

        if (empty($Builder)) {
            throwException(UploadFileException::GET_BUILDER_ERROR, $this->getAllowFormat());
        }
        return $Builder->init($this->getFile(),$this->getParameter());
    }

    /**
     * [checkAllowFormat description]
     * @Author    Boday
     * @DateTime  2018-04-26T16:20:06+0800
     * @param     [type]                    $file_format  [description]
     * @return    [type]                                  [description]
     */
    private function checkAllowFormat($file_format)
    {
        return in_array($file_format, $this->file_format_list);
    }

    /**
     * [getAllowFormat description]
     * @Author    Boday
     * @DateTime  2018-04-26T16:25:39+0800
     * @return    [type]                    [description]
     */
    private function getAllowFormat()
    {
        return implode(' , ', $this->file_format_list);
    }

    /**
     * [setFormat description]
     * @Author    Boday
     * @DateTime  2018-04-25T17:20:59+0800
     * @param     string                    $file_type  [description]
     */
    public function setFormat(string $file_format)
    {
        if (empty($this->checkAllowFormat($file_format))) {
            throwException(UploadFileException::TYPE_ERROR, $this->getAllowFormat());
        }
        $this->file_format = $file_format;
        return $this;
    }

    /**
     * [getFormat description]
     * @Author    Boday
     * @DateTime  2018-04-25T17:21:26+0800
     * @return    [type]                    [description]
     */
    public function getFormat()
    {
        return $this->file_format;
    }

    /**
     * [setFile description]
     * @Author    Boday
     * @DateTime  2018-04-26T16:31:02+0800
     * @param     [type]                    $Files  [description]
     */
    public function setFile($Files)
    {
        $this->Files = $Files;
        return $this;
    }

    /**
     * [getFile description]
     * @Author    Boday
     * @DateTime  2018-04-26T16:31:28+0800
     * @return    [type]                    [description]
     */
    public function getFile()
    {
        return $this->Files;
    }

    /**
     * @param $Parameter
     *
     * @return $this
     * @Author: Roy
     * @DateTime: 2021/2/19 上午 11:10
     */
    public function setParameter($Parameter)
    {
        $this->Parameter = $Parameter;
        return $this;
    }

    /**
     * @return mixed
     * @Author: Roy
     * @DateTime: 2021/2/19 上午 11:27
     */
    public function getParameter()
    {
        return $this->Parameter;
    }

    /**
     * [upload description]
     * @Author    Boday
     * @DateTime  2018-04-30T14:06:05+0800
     * @return    [type]                    [description]
     */
    public function upload()
    {

        return $this->Director->setBuilder($this->getBuilder())->upload();
    }

}
