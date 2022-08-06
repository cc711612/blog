<?php

namespace App\Http\Controllers\Api\Articles;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Validators\Api\Articles\ArticleStoreValidator;
use App\Models\Services\ArticleService;
use App\Models\Entities\ArticleEntity;
use App\Http\Requesters\Api\Articles\ArticleStoreRequest;
use App\Http\Requesters\Api\Articles\ArticleUpdateRequest;
use App\Http\Validators\Api\Articles\ArticleUpdateValidator;
use App\Http\Requesters\Api\Articles\ArticleDestroyRequest;
use App\Http\Validators\Api\Articles\ArticleDestroyValidator;
use App\Http\Resources\ArticleApiResource;

class ArticleController extends BaseController
{
    /**
     * @var \App\Models\Services\ArticleService
     */
    private $ArticleApiService;

    /**
     * @param  \App\Models\Services\ArticleService  $ArticleService
     */
    public function __construct(ArticleService $ArticleService)
    {
        $this->ArticleApiService = $ArticleService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/4/29 下午 09:49
     */
    public function index(Request $request)
    {
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => (new ArticleApiResource(
                $this->ArticleApiService
                    ->setRequest($request->toArray())
                    ->paginate()
            ))->paginate(),
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/8/13 下午 12:52
     */
    public function show(Request $request)
    {
        $id = Arr::get($request, 'article');
        $Article = $this->ArticleApiService->find($id);
        if (is_null($Article)) {
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
            'data'    => (new ArticleApiResource($Article))->show(),
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
        $Entity = $this->ArticleApiService->create($Requester->toArray());

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
        $Requester = (new ArticleUpdateRequest($request));

        $Validate = (new ArticleUpdateValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'   => false,
                'code'     => 400,
                'message'  => $Validate->errors(),
                'data'     => [],
                'redirect' => '',
            ]);
        }
        $Requester = $Requester->toArray();
        $Entity = $this->ArticleApiService->find(Arr::get($Requester, 'id'))
            ->update(Arr::get($Requester, ArticleEntity::Table));
        if ($Entity) {
            return response()->json([
                'status'   => true,
                'code'     => 200,
                'message'  => [],
                'data'     => [],
                'redirect' => route('article.index'),
            ]);
        }
        return response()->json([
            'status'   => false,
            'code'     => 400,
            'message'  => ['error' => '系統異常,請重新整理'],
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
        $Entity = $this->ArticleApiService->find(Arr::get($Requester, 'id'));
        if (is_null($Entity) === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['id' => 'not exist'],
            ]);
        }
        #刪除
        if ($Entity->update(Arr::get($Requester, ArticleEntity::Table))) {
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
