<?php

namespace App\Http\Controllers\Api\Comments;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requesters\Api\Comments\CommentStoreRequest;
use App\Http\Validators\Api\Comments\CommentStoreValidator;
use App\Models\Entities\CommentEntity;
use App\Models\Services\CommentService;
use App\Http\Requesters\Api\Comments\CommentIndexRequest;
use App\Http\Validators\Api\Comments\CommentIndexValidator;
use App\Http\Requesters\Api\Comments\CommentUpdateRequest;
use App\Http\Validators\Api\Comments\CommentUpdateValidator;
use App\Http\Requesters\Api\Comments\CommentDestroyRequest;
use App\Http\Validators\Api\Comments\CommentDestroyValidator;
use App\Models\Services\ArticleService;
use App\Jobs\sendLineMessage;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CommentApiResource;

class CommentController extends BaseController
{
    /**
     * @var \App\Models\Services\CommentService
     */
    private $CommentService;
    /**
     * @var \App\Models\Services\ArticleService
     */
    private $ArticleService;

    /**
     * @param  \App\Models\Services\CommentService  $CommentService
     * @param  \App\Models\Services\ArticleService  $ArticleService
     */
    public function __construct(
        CommentService $CommentService,
        ArticleService $ArticleService
    ) {
        $this->CommentService = $CommentService;
        $this->ArticleService = $ArticleService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:57
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

        $Comments = $this->CommentService
            ->setRequest($Requester->toArray())
            ->getCommentsByArticleId();

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => CommentApiResource::collection($Comments),
        ]);
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:58
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
        try {
            #Create
          $this->CommentService
                ->setRequest($Requester->toArray())
                ->createComment();

        } catch (\Exception $exception) {
            Log::channel()->error(sprintf("%s error params : %s", get_class($this),
                json_encode($exception, JSON_UNESCAPED_UNICODE)));
            return response()->json([
                'status'  => false,
                'code'    => 500,
                'message' => [],
                'data'    => [],
            ]);
        }

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [],
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:57
     */
    public function update(Request $request)
    {
        $Requester = (new CommentUpdateRequest($request));

        $Validate = (new CommentUpdateValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'   => false,
                'code'     => 400,
                'message'  => $Validate->errors(),
                'data'     => [],
                'redirect' => '',
            ]);
        }
        $Entity = $this->CommentService->setRequest($Requester->toArray())->updateComment();
        if ($Entity) {
            return response()->json([
                'status'   => true,
                'code'     => 200,
                'message'  => [],
                'data'     => [],
                'redirect' => route('article.show', ['article' => Arr::get($Requester, 'article_id')]),
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
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:57
     */
    public function destroy(Request $request)
    {
        $Requester = (new CommentDestroyRequest($request));
        $Validate = (new CommentDestroyValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
                'data'    => [],
            ]);
        }
        #刪除
        if (is_null($this->CommentService
                ->setRequest($Requester->toArray())
                ->delete()
            ) === false) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
                'data'    => [],
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => ['error' => '系統異常'],
            'data'    => [],
        ]);
    }
}
