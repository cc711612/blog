<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Entities\ArticleEntity;
use App\Observers\ArticleObserver;
use Illuminate\Pagination\Paginator;
use App\Models\Entities\CommentEntity;
use App\Observers\CommentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultstringLength(191);
        ArticleEntity::observe(ArticleObserver::class);
        CommentEntity::observe(CommentObserver::class);
        Paginator::defaultView('vendor.pagination.tailwind');
    }
}
