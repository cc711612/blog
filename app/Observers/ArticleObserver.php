<?php

namespace App\Observers;

use App\Models\Entities\ArticleEntity;
use App\Traits\CacheTrait;

class ArticleObserver
{
    use CacheTrait;
    /**
     * Handle the ArticleEntity "created" event.
     *
     * @param  \App\Models\Entities\ArticleEntity  $articleEntity
     * @return void
     */
    public function created(ArticleEntity $articleEntity)
    {
        //
        $this->forgetArticleIndexCache();
        return $this;
    }

    /**
     * Handle the ArticleEntity "updated" event.
     *
     * @param  \App\Models\Entities\ArticleEntity  $articleEntity
     * @return void
     */
    public function updated(ArticleEntity $articleEntity)
    {
        //
    }

    /**
     * Handle the ArticleEntity "deleted" event.
     *
     * @param  \App\Models\Entities\ArticleEntity  $articleEntity
     * @return void
     */
    public function deleted(ArticleEntity $articleEntity)
    {
        //
    }

    /**
     * Handle the ArticleEntity "restored" event.
     *
     * @param  \App\Models\Entities\ArticleEntity  $articleEntity
     * @return void
     */
    public function restored(ArticleEntity $articleEntity)
    {
        //
    }

    /**
     * Handle the ArticleEntity "force deleted" event.
     *
     * @param  \App\Models\Entities\ArticleEntity  $articleEntity
     * @return void
     */
    public function forceDeleted(ArticleEntity $articleEntity)
    {
        //
    }
}
