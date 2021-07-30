<?php
/**
 * @Author  : steatng
 * @DateTime: 2021/3/22 上午11:52
 */
namespace App\Concerns\Commons\Traits;

use App\Concerns\Commons\Abstracts\SupportAbstracts;
use Illuminate\Support\Arr;

/**
 * Trait TopicTrait
 *
 * @package App\Concerns\Commons\Traits
 * @Author  : steatng
 * @DateTime: 2021/3/22 上午11:55
 */
trait TopicTrait
{
    /**
     * @param $Topic
     *
     * @return array
     * @Author  : steatng
     * @DateTime: 2021/3/22 上午11:54
     */
    public function handleTopicScheme($Topic): array
    {
        return [
            'id'           => $Topic->id,
            'title'         => Arr::get($Topic, 'title'),
            'options' => Arr::except(Arr::get($Topic, 'options'), ['answer', 'explain']) ,
            'answer' => Arr::get($Topic, 'options.answer'),
            'explain' => Arr::get($Topic, 'options.explain'),
            'status'       => SupportAbstracts::getEnableDisableChecked($Topic->status),
        ];
    }

    /**
     * @param int $avg_score
     * @param int $member_examination_times
     *
     * @return string
     * @Author  : steatng
     * @DateTime: 2021/4/7 上午10:44
     */
    public function handleScoreComment(int $avg_score, int $member_examination_times = 0)
    {
        if($member_examination_times == 0){
            return '無';
        }

        $range = floor($avg_score / 10);

        $comment = '不及格';

        switch ($range){
            case 10:
            case 9:
                $comment = '優秀';
                break;
            case 8:
                $comment = '良好';
                break;
            case 7:
            case 6:
                $comment = '及格';
                break;
        }

        return $comment;
    }
}
