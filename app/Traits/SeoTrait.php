<?php
/**
 * @Author: Roy
 * @DateTime: 2022/12/15 上午 10:59
 */

namespace App\Traits;

use romanzipp\Seo\Services\SeoService;
use romanzipp\Seo\Facades\Seo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;

trait SeoTrait
{
    /**
     * @param  array  $Params
     *
     * @return $this
     * @Author: Roy
     * @DateTime: 2022/12/15 上午 10:59
     */
    private function setSeo(array $Params = [])
    {
        $seo = seo();
        $seo = app(SeoService::class);
        $seo = Seo::make();
        $description = is_null(Arr::get($Params, 'description')) ? config('meta.description')
            : Arr::get($Params, 'description');
        $keyword = is_null(Arr::get($Params, 'keyword')) ? config('meta.keyword') : Arr::get($Params, 'keyword');
        seo()->charset();
        seo()->title(Arr::get($Params, 'title', config('meta.title')));
        seo()->description($description);
        seo()->meta('keyword', $keyword);
        seo()->og('title', Arr::get($Params, 'title', config('meta.title')));
        seo()->og('description', $description);
        seo()->og('url', URL::current());
        seo()->og('site_name', Arr::get($Params, 'site_name', config('meta.site_name')));
        seo()->og('type', config('meta.type'));
        seo()->og('locale', config('meta.locale'));
        seo()->og('image', Arr::get($Params, 'image', config('meta.image')));
        seo()->viewport();
        seo()->csrfToken();
        return $this;
    }
}
