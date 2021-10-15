<?php

namespace App\Http\Validators\Api\Articles;

use App\Concerns\Commons\Abstracts\ValidatorAbstracts;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Concerns\Databases\Contracts\Request;

/**
 * Class ArticleStoreValidator
 *
 * @package App\Http\Validators\Api\Users
 * @Author: Roy
 * @DateTime: 2021/8/12 上午 12:43
 */
class ArticleStoreValidator extends ValidatorAbstracts
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
            'title' => [
                'required',
            ],
            'content' => [
                'required',
            ],
            'created_by' => [
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
            'title.required' => 'title 為必填',
            'content.required' => 'content 為必填',
            'created_by.required' => 'token 有誤',
            'status.required' => 'status 為必填',
        ];
    }
}
