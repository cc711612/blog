<?php

namespace App\Helpers\Trail;

use App\Helpers\Trail\Abstracts\TrailAbstract;
use App\Helpers\Trail\Exceptions\TrailException;
use App\Helpers\Trail\Trails\BreadcrumbTrail;
use App\Helpers\Trail\Trails\TitleTrail;

class TrailFactory extends TrailAbstract
{

    private $TrailMap;

    private $PathMapBag;

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-09-07T11:28:37+0800
     */
    public function __construct()
    {
        $this->TrailMap = collect([
            'Title'      => new TitleTrail,
            'Breadcrumb' => new BreadcrumbTrail,
        ]);
    }

    /**
     * [__clone description]
     * @Author    Boday
     * @DateTime  2017-09-07T11:26:10+0800
     * @return    [type]                    [description]
     */
    public function __clone()
    {
        $this->TrailMap = collect([
            'Title'      => new TitleTrail,
            'Breadcrumb' => new BreadcrumbTrail,
        ]);
    }

    /**
     * [validateArrayKey description]
     * @Author    Boday
     * @DateTime  2017-09-07T15:22:58+0800
     * @return    [type]                    [description]
     */
    private function validateArrayKey(array $Argument)
    {
        return (array_key_exists('text', $Argument) == false || array_key_exists('href', $Argument) == false);
    }

    /**
     * [setDefaultRoot description]
     * @Author    Boday
     * @DateTime  2017-09-07T11:35:53+0800
     * @param     array                     $Argument  [description]
     */
    public function setDefaultRoot(array $Argument)
    {
        // 如果基礎陣列鍵值不正確
        if ($this->validateArrayKey($Argument)) {
            throwException(TrailException::ARRAY_KEY_NOT_EXISTS);
            return;
        }

        $this->TrailMap->map(function ($Trail) use ($Argument) {
            $Trail->setDefaultRoot($Argument);
        });
    }

    /**
     * [getDefaultRoot description]
     * @Author    Boday
     * @DateTime  2017-09-07T11:49:48+0800
     * @return    [type]                    [description]
     */
    public function getDefaultRoot()
    {
        return $this->TrailMap->map(function ($Trail) {
            return $Trail->getDefaultRoot();
        });
    }

    /**
     * [setSeparator Only Title]
     * @Author    Boday
     * @DateTime  2017-09-08T08:49:35+0800
     * @param     string                    $Separator  [description]
     */
    public function setSeparator(string $Separator)
    {
        $Separator = trim($Separator);
        $this->TrailMap->get('Title')->setSeparator($Separator);
        return $this;
    }

    /**
     * [genPathMapBag description]
     * @Author    Boday
     * @DateTime  2017-09-07T15:30:11+0800
     * @param     [type]                    $ArgsNum   [description]
     * @param     [type]                    $ArgsList  [description]
     * @return    [type]                               [description]
     */
    private function genPathMapBag($ArgsNum, $ArgsList)
    {
        if ($ArgsNum < 1) {
            throwException(TrailException::NO_ARGUMENT);
            return;
        }

        $this->PathMapBag = [];
        foreach ($ArgsList as $Args) {
            if ($this->validateArrayKey($Args)) {
                foreach ($Args as $item) {
                    if ($this->validateArrayKey($item)) {
                        continue;
                    }
                    array_push($this->PathMapBag, $item);
                }
                continue;
            }
            array_push($this->PathMapBag, $Args);
        }
    }

    /**
     * [append description]
     * @Author    Boday
     * @DateTime  2017-09-07T15:36:10+0800
     * @return    [type]                    [description]
     */
    public function append()
    {
        $this->genPathMapBag(func_num_args(), func_get_args());
        $this->TrailMap->map(function ($Trail) {
            return $Trail->append($this->PathMapBag);
        });
        return $this;
    }

    /**
     * [prepend description]
     * @Author    Boday
     * @DateTime  2017-09-07T15:56:38+0800
     * @return    [type]                    [description]
     */
    public function prepend()
    {
        $this->genPathMapBag(func_num_args(), func_get_args());
        $this->TrailMap->map(function ($Trail) {
            return $Trail->prepend($this->PathMapBag);
        });
        return $this;
    }

    /**
     * [offset description]
     * @Author    Boday
     * @DateTime  2017-09-07T16:39:08+0800
     * @param     int                       $Num  [description]
     * @return    [type]                          [description]
     */
    public function offset(int $Num)
    {
        $this->TrailMap->map(function ($Trail) use ($Num) {
            return $Trail->offset($Num);
        });
        return $this;
    }

    /**
     * [asc description]
     * @Author    Boday
     * @DateTime  2017-09-07T16:56:41+0800
     * @return    [type]                    [description]
     */
    public function asc()
    {
        $this->TrailMap->map(function ($Trail) {
            return $Trail->asc();
        });
        return $this;
    }

    /**
     * [desc description]
     * @Author    Boday
     * @DateTime  2017-09-07T16:56:45+0800
     * @return    [type]                    [description]
     */
    public function desc()
    {
        $this->TrailMap->map(function ($Trail) {
            return $Trail->desc();
        });
        return $this;
    }

    /**
     * [reset description]
     * @Author    Boday
     * @DateTime  2017-09-08T14:01:34+0800
     * @return    [type]                    [description]
     */
    public function reset()
    {
        $this->TrailMap->map(function ($Trail) {
            return $Trail->reset();
        });
        return $this;
    }

    /**
     * [last description]
     * @Author    Boday
     * @DateTime  2017-09-29T09:18:41+0800
     * @return    [type]                    [description]
     */
    public function last()
    {
        $this->TrailMap->map(function ($Trail) {
            return $Trail->last();
        });
        return $this;
    }

    /**
     * [all description]
     * @Author    Boday
     * @DateTime  2017-09-07T16:04:46+0800
     * @return    [type]                    [description]
     */
    public function all()
    {
        $All = $this->TrailMap->map(function ($Trail) {
            return $Trail->all();
        });
        $this->reset();
        return $All;
    }

    /**
     * [Title description]
     * @Author    Boday
     * @DateTime  2017-09-07T17:31:15+0800
     */
    public function Title()
    {
        $All = $this->TrailMap->get('Title')->all();
        $this->reset();
        return $All;
    }

    /**
     * [Breadcrumb description]
     * @Author    Boday
     * @DateTime  2017-09-07T17:31:34+0800
     */
    public function Breadcrumb()
    {
        $All = $this->TrailMap->get('Breadcrumb')->all();
        $this->reset();
        return $All;
    }

}
