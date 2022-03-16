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
use App\Http\Requesters\Api\Comments\CommentUpdateRequest;
use App\Http\Validators\Api\Comments\CommentUpdateValidator;
use App\Http\Requesters\Api\Comments\CommentDestroyRequest;
use App\Http\Validators\Api\Comments\CommentDestroyValidator;
use App\Models\Services\Web\ArticleWebService;
use App\Models\Services\ArticleService;
use App\Jobs\sendLineMessage;
use Illuminate\Support\Facades\Log;

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
            'data'    => $Comments->map(function ($comment) {
                return [
                    'id'         => Arr::get($comment, 'id'),
                    'user'       => [
                        'id'    => Arr::get($comment, 'users.id'),
                        'name'  => Arr::get($comment, 'users.name'),
                        'image' => Arr::get($comment, 'users.images.cover', $this->getDefaultImage()),
                    ],
                    'content'    => Arr::get($comment, 'content'),
                    'updated_at' => Arr::get($comment, 'updated_at')->format('Y-m-d H:i:s'),
                    'logs'       => Arr::get($comment, 'logs', []),
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
        try {
            #Create
            $Entity = (new CommentEntity())->create(Arr::get($Requester, CommentEntity::Table));
            # 傳送LINE給user
            $ArticleUser = (new ArticleService())->getArticleUserSocialByArticleId(Arr::get($Requester, 'id'));
            if (is_null($ArticleUser) === false && is_null(Arr::get($ArticleUser,
                    'users.socials.0')) === false && is_null($Entity) === false) {
                sendLineMessage::dispatch(
                    [
                        'user_id' => Arr::get($ArticleUser, 'users.socials.0.social_type_value'),
                        'message' => sprintf("此文章有新留言~\n%s\n留言內容為:\n%s",
                            route("article.show", ['article' => Arr::get($Requester, 'id')]), $Entity->content),
                    ]
                );
            }
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
     * @DateTime: 2021/7/30 下午 02:03
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
        $Entity = (new CommentService())->setRequest($Requester->toArray())->update();
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
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 02:13
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
        if (is_null((new CommentService())
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

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/19 下午 01:30
     */
    private function getDefaultImage()
    {
        return sprintf('%s://%s%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER["HTTP_HOST"],
            config('filesystems.disks.images.url'), 'default.png');
    }

}
