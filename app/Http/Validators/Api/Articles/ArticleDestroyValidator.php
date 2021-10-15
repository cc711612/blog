<?php

namespace App\Http\Validators\Api\Articles;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;
use App\Models\Entities\ArticleEntity;

/**
 * Class ArticleDestroyValidator
 *
 * @package App\Http\Validators\Api\Articles
 * @Author: Roy
 * @DateTime: 2021/8/13 下午 09:41
 */
class ArticleDestroyValidator extends ValidatorAbstracts
{
    /**
     * @var \App\Concerns\Databases\Contracts\Request
     */
    protected $request;

    /**
     * ArticleDestroyValidator constructor.
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
        ];
    }
}
