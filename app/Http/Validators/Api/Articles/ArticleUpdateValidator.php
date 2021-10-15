<?php

namespace App\Http\Validators\Api\Articles;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;
use App\Models\Entities\ArticleEntity;

/**
 * Class ArticleUpdateValidator
 *
 * @package App\Http\Validators\Api\Articles
 * @Author: Roy
 * @DateTime: 2021/8/13 下午 09:41
 */
class ArticleUpdateValidator extends ValidatorAbstracts
{
    /**
     * @var \App\Concerns\Databases\Contracts\Request
     */
    protected $request;

    /**
     * ArticleUpdateValidator constructor.
     *
     * @param  \App\Concerns\Databases\Contracts\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/8/13 下午 09:41
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
            'id'=>[
                'required',
                Rule::exists(ArticleEntity::Table)->where(function ($query) {
                    return $query
                        ->where('id', Arr::get($this->request,'id'))
                        ->where('user_id', Arr::get($this->request,'updated_by'))
                        ->whereNull('deleted_at')
                        ;
                }),
            ],

            'title' => [
                'required',
            ],
            'content' => [
                'required',
            ],
            'updated_by' => [
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
            'id.required' => 'id 為必填',
            'id.exists' => 'id 不存在',
            'title.required' => 'title 為必填',
            'content.required' => 'content 為必填',
            'updated_by.required' => 'token 有誤',
            'status.required' => 'status 為必填',
        ];
    }
}
