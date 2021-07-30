<?php
namespace App\Helpers\Metas;

class MetaClassify
{
    public $FilterMapBag;

    public function init($Config)
    {
        if ($this->FilterMapBag) {
            return;
        }

        // 建立一下 搜尋包
        $this->FilterMapBag = collect($Config)->map(function ($value, $key) {
            $value = collect($value)->keyBy(function ($item) {
                return strtolower($item);
            })->all();
            return collect($value)->map(function ($sub_value, $sub_key) use ($key) {
                return ['BuilderName' => $key, 'MainAttributeValue' => $sub_value];
            })->all();
        });
        //$this->FilterMapBag = $this->FilterMapBag->collapse();
    }

    /**
     * [getRealKey description]
     * @Author    Boday
     * @DateTime  2017-08-29T20:26:39+0800
     * @param     [type]                    $KeyName  [description]
     * @return    [type]                              [description]
     */
    public function getRealKey(string $KeyName)
    {

        $KeyName = strtolower($KeyName);

        return $this->FilterMapBag->map(function ($Item, $Key) use ($KeyName) {
            $Result = array_filter(array_keys($Item), function ($item) use ($KeyName) {
                return ($item == $KeyName || preg_match('/^[^:]+:' . $KeyName . '$/', $item));
            });
            if ($Result) {
                $ItemKey = implode('', $Result);
                return $Item[$ItemKey];
            }
        })->filter();
    }

    /**
     * [getFilterMapBag description]
     * @Author    Boday
     * @DateTime  2017-08-31T15:44:41+0800
     * @return    [type]                    [description]
     */
    public function getFilterMapBag()
    {
        return $this->FilterMapBag;
    }
}
