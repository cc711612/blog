<?php

namespace App\Helpers\Exception;

use App\Helpers\Exception\DevelopException;

class ExceptionRegister
{

    // 預設的 Exception 資訊
    const EXCEPTION_CONST_ERROR = [
        'Code'    => 666001,
        'Message' => '你的 EXCEPTION CONST 必須要是 array 格式，並且包含 Code & Message!!!',
    ];

    public function throwException()
    {

        // 整理串入的參數資訊
        $Argument          = func_get_args();
        $throwExceptionBag = $Argument['0'];
        array_forget($Argument, 0);

        if (is_array($throwExceptionBag) == false) {
            $this->throwExceptionBag(self::EXCEPTION_CONST_ERROR);
            return;
        }

        // 整理有待其他參數的設定值
        if (empty($Argument) == false) {
            $throwExceptionBag['Message'] = vsprintf($throwExceptionBag['Message'], $Argument);
        }

        // 直接丟了吧
        $this->throwExceptionBag($throwExceptionBag);
    }

    /**
     * [throwExceptionBag description]
     * @Author    Boday
     * @DateTime  2017-09-11T15:58:06+0800
     * @param     array                     $ExceptionBag  [description]
     * @return    [type]                                   [description]
     */
    private function throwExceptionBag(array $ExceptionBag)
    {
        throw new DevelopException($ExceptionBag['Message'], $ExceptionBag['Code']);
    }

}
