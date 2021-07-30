<?php

namespace App\Helpers\Trail\Trails;

use App\Helpers\Trail\Interfaces\TrailInterface;

class BreadcrumbTrail implements TrailInterface
{
    private $DefaultRoot;

    private $PathMapBag;
    private $PathFilterMapBag;

    public $OrderBy = self::ASC;

    public $OffSet = 0;

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-09-07T14:19:51+0800
     */
    public function __construct()
    {
        $this->PathMapBag = collect($this->PathMapBag);
    }

    /**
     * [setDefaultRoot description]
     * @Author    Boday
     * @DateTime  2017-09-07T11:35:53+0800
     * @param     array                     $Argument  [description]
     */
    public function setDefaultRoot(array $Argument)
    {
        $this->DefaultRoot = $Argument;
    }

    /**
     * [getDefaultRoot description]
     * @Author    Boday
     * @DateTime  2017-09-07T11:52:56+0800
     * @return    [type]                    [description]
     */
    public function getDefaultRoot()
    {
        return $this->DefaultRoot;
    }

    /**
     * [append description]
     * @Author    Boday
     * @DateTime  2017-09-07T15:54:38+0800
     * @param     array                     $PathMapBag  [description]
     * @return    [type]                                 [description]
     */
    public function append(array $PathMapBag)
    {
        $this->PathMapBag = $this->PathMapBag->push($PathMapBag);
        return $this->PathMapBag->collapse()->all();
    }

    /**
     * [prepend description]
     * @Author    Boday
     * @DateTime  2017-09-07T16:03:42+0800
     * @param     array                     $PathMapBag  [description]
     * @return    [type]                                 [description]
     */
    public function prepend(array $PathMapBag)
    {
        $this->PathMapBag = $this->PathMapBag->prepend($PathMapBag);
        return $this->PathMapBag->collapse()->all();
    }

    /**
     * [offset description]
     * @Author    Boday
     * @DateTime  2017-09-07T16:42:12+0800
     * @param     [type]                    $Num  [description]
     * @return    [type]                          [description]
     */
    public function offset($Num)
    {
        $this->PathFilterMapBag ?? $this->reset();
        $this->PathFilterMapBag = array_slice($this->PathFilterMapBag, $Num);
        return $this->PathFilterMapBag;
    }

    /**
     * [asc description]
     * @Author    Boday
     * @DateTime  2017-09-07T17:03:58+0800
     * @return    [type]                    [description]
     */
    public function asc()
    {
        $this->PathFilterMapBag ?? $this->reset();
        ksort($this->PathFilterMapBag);
        return $this->PathFilterMapBag;
    }

    /**
     * [desc description]
     * @Author    Boday
     * @DateTime  2017-09-07T17:04:01+0800
     * @return    [type]                    [description]
     */
    public function desc()
    {
        $this->PathFilterMapBag ?? $this->reset();
        krsort($this->PathFilterMapBag);
        return $this->PathFilterMapBag;
    }

    /**
     * [reset description]
     * @Author    Boday
     * @DateTime  2017-09-08T14:02:19+0800
     * @return    [type]                    [description]
     */
    public function reset()
    {
        unset($this->PathFilterMapBag);
        $this->PathFilterMapBag = $this->PathMapBag->collapse()->filter()->all();
        if ($this->DefaultRoot) {
            array_unshift($this->PathFilterMapBag, $this->DefaultRoot);
        }
    }

    /**
     * [last description]
     * @Author    Boday
     * @DateTime  2017-09-29T09:43:54+0800
     * @return    [type]                    [description]
     */
    public function last()
    {
        $this->PathFilterMapBag ?? $this->reset();
        $this->PathFilterMapBag = [end($this->PathFilterMapBag)];
        return $this->PathFilterMapBag;
    }

    /**
     * [all description]
     * @Author    Boday
     * @DateTime  2017-09-07T16:31:45+0800
     * @return    [type]                    [description]
     */
    public function all()
    {
        $this->PathFilterMapBag ?? $this->reset();
        return $this->PathFilterMapBag;
    }

}
