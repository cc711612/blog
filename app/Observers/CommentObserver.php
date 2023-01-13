<?php

namespace App\Observers;

use App\Models\Entities\CommentEntity;
use Illuminate\Support\Arr;
use App\Jobs\sendLineMessage;
use App\Models\Services\ArticleService;

class CommentObserver
{
    /**
     * Handle the comments "created" event.
     *
     * @param  App\Models\Entities\CommentEntity  $CommentEntity
     *
     * @return void
     */
    public function created(CommentEntity $CommentEntity)
    {
        //
        $ArticleUser = app(ArticleService::class)->getArticleUserSocialByArticleId($CommentEntity->article_id);
        if (is_null($ArticleUser) === false && is_null(Arr::get($ArticleUser,
                'users.socials.0')) === false) {
            sendLineMessage::dispatch(
                [
                    'user_id' => Arr::get($ArticleUser, 'users.socials.0.social_type_value'),
                    'message' => sprintf("此文章有新留言~\n%s\n留言內容為:\n%s",
                        route("article.show", ['article' => $CommentEntity->article_id]), $CommentEntity->content),
                ]
            );
        }
    }

    /**
     * Handle the comments "updated" event.
     *
     * @param  App\Models\Entities\CommentEntity  $CommentEntity
     *
     * @return void
     */
    public function updated(CommentEntity $CommentEntity)
    {
        //
    }

    /**
     * Handle the comments "deleted" event.
     *
     * @param  App\Models\Entities\CommentEntity  $CommentEntity
     *
     * @return void
     */
    public function deleted(CommentEntity $CommentEntity)
    {
        //
    }

    /**
     * Handle the comments "restored" event.
     *
     * @param  App\Models\Entities\CommentEntity  $CommentEntity
     *
     * @return void
     */
    public function restored(CommentEntity $CommentEntity)
    {
        //
    }

    /**
     * Handle the comments "force deleted" event.
     *
     * @param  App\Models\Entities\CommentEntity  $CommentEntity
     *
     * @return void
     */
    public function forceDeleted(CommentEntity $CommentEntity)
    {
        //
    }
}
