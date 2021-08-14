<?php

namespace App\Http\Controllers\Api\Comments;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Traits\ApiPaginateTrait;
use App\Http\Requesters\Api\Comments\CommentStoreRequest;
use App\Http\Validators\Api\Comments\CommentStoreValidator;
use App\Models\Entities\CommentEntity;
use App\Models\Services\CommentService;
use App\Http\Requesters\Api\Comments\CommentIndexRequest;
use App\Http\Validators\Api\Comments\CommentIndexValidator;

class CommentController extends BaseController
{
    use ApiPaginateTrait;

    /**
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:04
     */
    public function index(Request $request)
    {
        $Requester = (new CommentIndexRequest($request));
        $Validate = (new CommentIndexValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'   => false,
                'code'     => 400,
                'message'  => $Validate->errors(),
                'data'     => [],
                'redirect' => '',
            ]);
        }

        $Comments = (new CommentService())
            ->setRequest($Requester->toArray())
            ->getCommentsByArticleId();

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => $Comments->map(function ($comment){
                return [
                    'id'         => Arr::get($comment, 'id'),
                    'user_id'    => Arr::get($comment, 'user_id'),
                    'user_name'  => Arr::get($comment, 'users.name'),
                    'content'    => Arr::get($comment, 'content'),
                    'updated_at' => Arr::get($comment, 'updated_at')->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 02:01
     */
    public function store(Request $request)
    {

        $Requester = (new CommentStoreRequest($request));

        $Validate = (new CommentStoreValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'   => false,
                'code'     => 400,
                'message'  => $Validate->errors(),
                'data'     => [],
                'redirect' => '',
            ]);
        }
        #Create
        $Entity = (new CommentEntity())->create(Arr::get($Requester,CommentEntity::Table));
        return response()->json([
            'status'   => true,
            'code'     => 200,
            'message'  => [],
            'data'     => [],
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 02:03
     */
    public function update(Request $request)
    {
        $Requester = (new ArticleUpdateRequest($request));

        $Validate = (new ArticleUpdateValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
                'data'     => [],
                'redirect' => '',
            ]);
        }
        $Requester = $Requester->toArray();
        $Entity = (new ArticleEntity())->find(Arr::get($Requester, 'id'))
            ->update(Arr::get($Requester,ArticleEntity::Table));
        if ($Entity) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
                'data'     => [],
                'redirect' => route('article.index')
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => ['error' => '系統異常,請重新整理'],
            'data'     => [],
            'redirect' => '',
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 02:13
     */
    public function destroy(Request $request)
    {
        $Requester = (new ArticleDestroyRequest($request));

        $Validate = (new ArticleDestroyValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
            ]);
        }
        $Entity = (new ArticleEntity())->find(Arr::get($Requester, 'id'));
        if (is_null($Entity) === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['id' => 'not exist'],
            ]);
        }
        #刪除
        if ($Entity->update(Arr::get($Requester,ArticleEntity::Table))) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => ['error' => '系統異常'],
        ]);
    }


}
