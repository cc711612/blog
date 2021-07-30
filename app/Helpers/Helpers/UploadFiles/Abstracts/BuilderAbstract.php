<?php
namespace App\Helpers\UploadFiles\Abstracts;

use App\Concerns\Databases\Service;
use App\Helpers\UploadFiles\Exceptions\UploadFileException;
use Illuminate\Support\Facades\Storage;

abstract class BuilderAbstract
{
    protected $Files;

    protected $file_path;
    protected $file_full_name;
    protected $file_name = 'origin';
    protected $file_extension;
    protected $file_storage_path;
    protected $file_url;
    protected $audio;

    protected $Entity;
    protected $Service;


    abstract protected function genCenterFilePath();
    abstract protected function genClientFilePath();
    abstract protected function genUpdateData():array;

    /**
     * @return string[]
     * @Author: Roy
     * @DateTime: 2021/2/19 下午 06:03
     */
    public function getAudio()
    {
        return ['mp3','m3u','wav','mid'];
    }

    /**
     * @return string[]
     * @Author: Roy
     * @DateTime: 2021/2/19 下午 06:03
     */
    public function getHandout()
    {
        return ['pdf','docs','docx','ppt'];
    }
    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-14 17:17
     */
    public function getService() : Service
    {
        return $this->Service;
    }

    /**
     * @param \App\Concerns\Databases\Service $Service
     *
     * @return \App\Helpers\UploadFiles\Abstracts\BuilderAbstract
     * @Author  : daniel
     * @DateTime: 2020-05-14 17:17
     */
    public function setService(Service $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * [setFile description]
     * @Author    Boday
     * @DateTime  2018-04-26T16:31:02+0800
     * @param     [type]                    $Files  [description]
     */
    protected function setFile($Files)
    {
        $this->Files = $Files;
        return $this;
    }

    /**
     * [getFile description]
     * @Author    Boday
     * @DateTime  2018-04-30T14:08:32+0800
     * @return    [type]                    [description]
     */
    protected function getFile()
    {
        return $this->Files;
    }

    /**
     * [getFileName description]
     * @Author    Boday
     * @DateTime  2018-04-30T16:32:55+0800
     * @return    [type]                    [description]
     */
    protected function getFileName()
    {
        return $this->file_name;
    }

    /**
     * [getCenterFilePathFormat description]
     * @Author    Boday
     * @DateTime  2018-04-30T15:22:05+0800
     * @return    [type]                    [description]
     */
    protected function getCenterFilePathFormat()
    {
        return $this->center_file_path_format;
    }

    /**
     * [getClientFilePathFormat description]
     * @Author    Boday
     * @DateTime  2018-04-30T15:22:08+0800
     * @return    [type]                    [description]
     */
    protected function getClientFilePathFormat()
    {
        return $this->client_file_path_format;
    }

    /**
     * [setFilePath description]
     * @Author    Boday
     * @DateTime  2018-04-30T15:43:03+0800
     * @param     [type]                    $file_path  [description]
     */
    private function setFilePath($file_path)
    {
        $this->file_path = $file_path;
        return $this;
    }

    /**
     * [getFilePath description]
     * @Author    Boday
     * @DateTime  2018-04-30T16:52:38+0800
     * @return    [type]                    [description]
     */
    private function getFilePath()
    {
        return $this->file_path;
    }

    /**
     * [genExtension description]
     * @Author    Boday
     * @DateTime  2018-04-30T16:33:39+0800
     * @return    [type]                    [description]
     */
    public function genExtension()
    {

        if (in_array($this->getFile()->getMimeType(), array_keys($this->extension_list)) == false) {
            throwException(UploadFileException::IMAGE_MIME_TYPE_ERROR);
        }

        $this->file_extension = $this->extension_list[$this->getFile()->getMimeType()];
        return $this;
    }

    /**
     * [getExtension description]
     * @Author    Boday
     * @DateTime  2018-04-30T16:34:04+0800
     * @return    [type]                    [description]
     */
    protected function getExtension()
    {
        return $this->file_extension;
    }

    /**
     * [genFilePath 產生檔案路徑]
     * @Author    Boday
     * @DateTime  2018-04-30T15:08:34+0800
     * @return    [type]                    [description]
     */
    public function genFilePath()
    {
        $this->setFilePath($this->genCenterFilePath());
        return $this;
    }

    /**
     * [getFileFullName description]
     * @Author    Boday
     * @DateTime  2018-04-30T16:54:02+0800
     * @return    [type]                    [description]
     */
    private function getFileFullName()
    {
        return $this->file_name . '.' . $this->file_extension;
    }

    /**
     * [getFileStoragePath description]
     * @Author    Boday
     * @DateTime  2018-04-30T17:05:33+0800
     * @return    [type]                    [description]
     */
    private function getFileStoragePath()
    {
        return $this->file_storage_path;
    }

    /**
     * [saveFile description]
     * @Author    Boday
     * @DateTime  2018-04-30T16:50:00+0800
     * @return    [type]                    [description]
     */
    public function saveFile()
    {
        $path = $this->getConfigFilePath();

        $this->file_storage_path = Storage::disk($path)->putFileAs(
            $this->getFilePath(),
            $this->getFile(),
            $this->getFileFullName()
        );
        return $this;
    }

    /**
     * [genFileUrl description]
     * @Author    Boday
     * @DateTime  2018-04-30T17:06:28+0800
     * @return    [type]                    [description]
     */
    public function genFileUrl()
    {
        $path = $this->getConfigFilePath();
        $url = $this->getConfigUrl();

        $this->file_url = Storage::disk($path)->url($this->file_storage_path);
        if (config('app.storage_name') == 's3') {
            $this->file_url = str_replace(config('app.storage_s3_path'), $url, $this->file_url);
        }

        return $this;
    }

    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     * @Author: Roy
     * @DateTime: 2021/2/19 下午 06:05
     */
    public function getConfigFilePath()
    {
        #音檔
        if(in_array($this->getExtension(),$this->getAudio())){
            return config('app.storage_audio_name');
        }elseif(in_array($this->getExtension(),$this->getHandout())){
            #教材
            return config('app.storage_handout_name');
        }
        return config('app.storage_name');
    }
    public function getConfigUrl()
    {
        #音檔
        if(in_array($this->getExtension(),$this->getAudio())){
            return config('filesystems.disks.audio.url');
        }elseif(in_array($this->getExtension(),$this->getHandout())){
            #教材
            return config('filesystems.disks.handout.url');
        }
        return config('filesystems.disks.public.url');
    }
    /**
     * [getFileURL description]
     * @Author    Boday
     * @DateTime  2018-04-30T17:16:45+0800
     * @return    [type]                    [description]
     */
    protected function getFileURL()
    {
        return $this->file_url;
    }

    /**
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020-05-14 17:18
     */
    public function createDraft()
    {
        $this->Entity = $this->getService()->getDraft();
        return $this;
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
     * @Author: Roy
     * @DateTime: 2021/2/19 上午 11:47
     */
    public function setFileName($FileName)
    {
        $this->file_name = $FileName;
        return $this;
    }
    /**
     * [upload description]
     * @Author    Boday
     * @DateTime  2018-04-30T17:14:35+0800
     * @return    [type]                    [description]
     */
    public function upload()
    {
        return $this->getService()->update(
            $this->Entity->id,
            $this->genUpdateData()
        );
    }

}
