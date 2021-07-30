<?php
namespace App\Helpers\UploadFiles\Directors;

use App\Helpers\UploadFiles\Abstracts\BuilderAbstract;

class Director
{

    private $Builder;
    /**
     * [setBuilder description]
     * @Author    Boday
     * @DateTime  2018-04-30T10:54:14+0800
     */
    public function setBuilder(BuilderAbstract $Builder)
    {
        $this->Builder = $Builder;
        return $this;
    }

    /**
     * [upload description]
     * @Author    Boday
     * @DateTime  2018-04-30T10:31:45+0800
     * @return    [type]                    [description]
     */
    public function upload()
    {
        return $this->Builder
            ->genExtension()
            ->createDraft()
            ->genFilePath()
            ->saveFile()
            ->genFileUrl()
            ->upload();
    }

}
