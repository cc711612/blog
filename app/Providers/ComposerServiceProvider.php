<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // AdminSidebar
        $this->ComposeMain();
    }

    /**
     * ComposeStoreMain
     *
     * 被register呼叫，指定store_Main視圖與callback function
     */
    public function ComposeMain()
    {
        view()->composer('blog.*', \App\Http\Composers\Mains\MainComposer::class);
    }
}
