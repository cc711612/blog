<?php
namespace App\Concerns\Commons\Traits\Apis;

use App\Modules\Books\Databases\Entities\BookEntity;
use App\Modules\Identifications\FlagTypes\Contracts\Constants\FlagType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Trait FlagTrait
 *
 * @package App\Concerns\Commons\Traits\Apis
 * @Author  : daniel
 * @DateTime: 2021/6/2 下午6:10
 */
trait FlagTrait
{
    /**
     * @param \App\Modules\Books\Databases\Entities\BookEntity $BookEntity
     *
     * @return array|null
     * @Author  : daniel
     * @DateTime: 2021/6/2 下午6:10
     */
    public function flag(BookEntity $BookEntity)
    {
        //設計用來輸出flag
        // return array()
        $Result = [];
        //Hot

        //New Start
        $start_time = Carbon::parse($BookEntity->start_at);
        $now_time = Carbon::now();

        if (is_null($BookEntity->start_at) == false) {
            //先確定start_time 小於 現在
            if ($now_time->gt($start_time) == true && $now_time->diffInDays($start_time) < 7) {
                $Result[] = FlagType::FLAG_TYPE_NEW;
            }
        }
        //New End

        //測試資料
//        if(config('app.env') != 'production'){
//            if($BookEntity->id > 400){
//                $Result[] = FlagType::FLAG_TYPE_NEW;
//            }
//
//            if($BookEntity->id > 350){
//                $Result[] = FlagType::FLAG_TYPE_HOT;
//            }
//        }

        if (empty($Result)) {
            return null;
        }

        return array_values(array_unique($Result));
    }
}
