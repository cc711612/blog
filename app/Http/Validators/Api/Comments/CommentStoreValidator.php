<?php

namespace App\Http\Validators\Api\Comments;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;
use App\Models\Entities\ArticleEntity;

/**
 * Class CommentStoreValidator
 *
 * @package App\Http\Validators\Api\Comments
 * @Author: Roy
 * @DateTime: 2021/8/14 上午 10:52
 */
class CommentStoreValidator extends ValidatorAbstracts
{
    /**
     * @var \App\Concerns\Databases\Contracts\Request
     */
    protected $request;

    /**
     * UserStoreValidator constructor.
     *
     * @param  \App\Concerns\Databases\Contracts\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:16
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \string[][]
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:59
     */
    protected function rules(): array
    {
        return [
            'id' => [
                'required',
                Rule::exists(ArticleEntity::Table)->where(function ($query) {
                    return $query
                        ->where('id', Arr::get($this->request,'id'))
                        ->whereNull('deleted_at')
                        ;
                }),
            ],
            'content' => [
                'required',
            ],
            'user_id' => [
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }

    /**
     * @return string[]
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:59
     */
    protected function messages(): array
    {
        return [
            'id.required' => '文章不存在',
            'id.exists' => '文章不存在',
            'content.required' => '留言內容 為必填',
            'user_id.required' => '帳號資訊 有誤',
            'status.required' => 'status 為必填',
        ];
    }
}
