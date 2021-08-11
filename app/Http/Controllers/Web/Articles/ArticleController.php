<?php

namespace App\Http\Controllers\Web\Articles;

use App\Models\UserEntity;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Validators\Api\Users\UserStoreValidator;
use App\Http\Requesters\Api\Users\UserStoreRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requesters\Api\Users\UserUpdateRequest;
use App\Http\Validators\Api\Users\UserUpdateValidator;
use App\Http\Requesters\Api\Users\UserDestroyRequest;
use App\Http\Validators\Api\Users\UserDestroyValidator;
use App\Models\Services\ArticleService;
use Illuminate\Support\Str;
use App\Models\Entities\ArticleEntity;

class ArticleController extends BaseController
{
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

        $Html = (object) [
            'elements'  => $Articles->getCollection()->map(function ($article) {
                return (object) [
                    'id'         => Arr::get($article, 'id'),
                    'title'      => Arr::get($article, 'title'),
                    'content'    => Arr::get($article, 'content'),
                    'sub_title'  => Str::limit(strip_tags(Arr::get($article, 'content')), 30, '...'),
                    'user_name'  => Arr::get($article, 'users.name'),
                    'updated_at' => Arr::get($article, 'updated_at')->format('Y-m-d H:i:s'),
                    'actions'    => (object) [
                        'show_uri'   => route('article.show', ['article' => Arr::get($article, 'id')]),
                        'edit_uri'   => route('article.edit', ['article' => Arr::get($article, 'id')]),
                        'delete_uri' => route('article.destroy', ['article' => Arr::get($article, 'id')]),
                    ],
                ];
            }),
            'page_link' => $Articles->links()->toHtml(),
        ];

        return view('blog.index', compact('Html'));
    }

    public function show(Request $request)
    {
        $id = Arr::get($request, 'article');
        $article = (new ArticleService())->find($id);

        $Html = (object) [
            'element' => (object) [
                'id'         => Arr::get($article, 'id'),
                'title'      => Arr::get($article, 'title'),
                'content'    => Arr::get($article, 'content'),
                'sub_title'  => Str::limit(strip_tags(Arr::get($article, 'content')), 30, '...'),
                'user_name'  => Arr::get($article, 'users.name'),
                'updated_at' => Arr::get($article, 'updated_at')->format('Y-m-d H:i:s'),
                'comments'   => Arr::get($article, 'comments'),
                'actions'    => (object) [
                    'show_uri'   => route('article.show', ['article' => Arr::get($article, 'id')]),
                    'edit_uri'   => route('article.edit', ['article' => Arr::get($article, 'id')]),
                    'delete_uri' => route('article.destroy', ['article' => Arr::get($article, 'id')]),
                ],
            ],
        ];
        return view('blog.show', compact('Html'));
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
        $Requester = (new UserStoreRequest($request));

        $Validate = (new UserStoreValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
            ]);
        }
        $Requester = $Requester->toArray();
        Arr::set($Requester, 'password', Hash::make(Arr::get($Requester, 'password')));
        #Create
        (new UserEntity())->create($Requester);
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
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

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 02:32
     */
    public function getDefaultImage()
    {
        return sprintf('%s://%s%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER["HTTP_HOST"],
            config('filesystems.disks.images.url'), 'default.png');
    }
}
