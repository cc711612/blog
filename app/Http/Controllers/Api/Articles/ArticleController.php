<?php

namespace App\Http\Controllers\Api\Articles;

use pp\Models\UserEntity;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Validators\Api\Articles\ArticleStoreValidator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requesters\Api\Users\UserUpdateRequest;
use App\Http\Validators\Api\Users\UserUpdateValidator;
use App\Http\Requesters\Api\Users\UserDestroyRequest;
use App\Http\Validators\Api\Users\UserDestroyValidator;
use App\Models\Services\ArticleService;
use Illuminate\Support\Str;
use App\Models\Entities\ArticleEntity;
use Illuminate\Support\Facades\Auth;
use App\Http\Requesters\Api\Articles\ArticleStoreRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Traits\ApiPaginateTrait;

class ArticleController extends BaseController
{
    use ApiPaginateTrait;

    /**
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:04
     */
    public function index(Request $request)
    {
        $Articles = (new ArticleService())
            ->setRequest($request->toArray())
            ->paginate();

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'paginate' => $this->handleApiPageInfo($Articles),
                'articles' => $Articles->getCollection()->map(function ($article) {
                    return [
                        'id'         => Arr::get($article, 'id'),
                        'title'      => Arr::get($article, 'title'),
                        'content'    => Arr::get($article, 'content'),
                        'sub_title'  => Str::limit(strip_tags(Arr::get($article, 'content')), 30, '...'),
                        'user_name'  => Arr::get($article, 'users.name'),
                        'user_id'    => Arr::get($article, 'user_id'),
                        'updated_at' => Arr::get($article, 'updated_at')->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
        ]);
    }

    public function show(Request $request)
    {
        $id = Arr::get($request, 'article');
        $article = (new ArticleService())->find($id);
        if (is_null($article)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => [],
                'data'    => [],
            ]);
        }
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'id'         => Arr::get($article, 'id'),
                'title'      => Arr::get($article, 'title'),
                'content'    => Arr::get($article, 'content'),
                'sub_title'  => Str::limit(strip_tags(Arr::get($article, 'content')), 30, '...'),
                'user_name'  => Arr::get($article, 'users.name'),
                'updated_at' => Arr::get($article, 'updated_at')->format('Y-m-d H:i:s'),
                'comments'   => Arr::get($article, 'comments', collect([]))->map(function ($comment) {
                    return [
                        'id'         => Arr::get($comment, 'id'),
                        'user_id'    => Arr::get($comment, 'user_id'),
                        'user_name'  => Arr::get($comment, 'users.name'),
                        'content'    => Arr::get($comment, 'content'),
                        'updated_at' => Arr::get($comment, 'updated_at')->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
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
        $Requester = (new ArticleStoreRequest($request));

        $Validate = (new ArticleStoreValidator($Requester))->validate();
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
        $Entity = (new ArticleEntity())->create($Requester->toArray());

        return response()->json([
            'status'   => true,
            'code'     => 200,
            'message'  => [],
            'data'     => [],
            'redirect' => route('article.index'),
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
        $Requester = (new UserUpdateRequest($request));

        $Validate = (new UserUpdateValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
            ]);
        }
        $Requester = $Requester->toArray();
        Arr::set($Requester, 'users.password', Hash::make(Arr::get($Requester, 'users.password')));
        #Create
        $Entity = (new UserEntity())->find(Arr::get($Requester, 'id'))
            ->update($Requester);
        if ($Entity) {
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

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 02:13
     */
    public function destroy(Request $request)
    {
        $Requester = (new UserDestroyRequest($request));

        $Validate = (new UserDestroyValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
            ]);
        }
        $Entity = (new UserEntity())->find(Arr::get($Requester, 'id'));
        if (is_null($Entity) === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['id' => 'not exist'],
            ]);
        }
        #刪除
        if ($Entity->delete()) {
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
