<?php

namespace App\Http\Controllers\Web\Articles;

use App\Models\UserEntity;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Validators\Api\Users\ArticleStoreValidator;
use App\Http\Requesters\Api\Users\ArticleStoreRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requesters\Api\Users\UserUpdateRequest;
use App\Http\Validators\Api\Users\UserUpdateValidator;
use App\Http\Requesters\Api\Users\UserDestroyRequest;
use App\Http\Validators\Api\Users\UserDestroyValidator;
use App\Models\Services\ArticleService;
use Illuminate\Support\Str;
use App\Models\Entities\ArticleEntity;
use Illuminate\Support\Facades\Auth;
use romanzipp\Seo\Facades\Seo;
use romanzipp\Seo\Services\SeoService;

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
        $this->setSeo([
            'title'       => config('app.name'),
            'description' => '文章列表',
        ]);
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
                        'delete_uri' => route('api.article.destroy', ['article' => Arr::get($article, 'id')]),
                        'user_uri'   => route('article.index', ['user' => Arr::get($article, 'user_id')]),
                    ],
                    'is_editor'  => Auth::id() === Arr::get($article, 'user_id'),
                ];
            }),
            'page_link' => $Articles->appends(request(['user']))->links()->toHtml(),
        ];
        return view('blog.index', compact('Html'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @Author: Roy
     * @DateTime: 2021/8/12 下午 10:07
     */
    public function show(Request $request)
    {
        $id = Arr::get($request, 'article');
        $article = (new ArticleService())->find($id);
        if (is_null($article) === true) {
            return redirect()->route('article.index');
        }
        $this->setSeo([
            'title'       => Arr::get($article, 'title'),
            'description' => is_null(Arr::get($article, 'seo.description'))
                ?  preg_replace('/\s(?=)/', '',
                    Str::limit(strip_tags(Arr::get($article, 'content')), 100, '...'))
                :Arr::get($article, 'seo.description'),
            'keyword'     => Arr::get($article, 'seo.keyword'),
        ]);
        $Html = (object) [
            'element'      => (object) [
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
                    'delete_uri' => route('api.article.destroy', ['article' => Arr::get($article, 'id')]),
                    'user_uri'   => route('article.index', ['user' => Arr::get($article, 'user_id')]),
                ],
            ],
            'member_token' => is_null(Auth::id()) ? '' : Arr::get(Auth::user(), 'api_token'),
        ];
        return view('blog.show', compact('Html'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @Author: Roy
     * @DateTime: 2021/8/12 下午 10:07
     */
    public function create(Request $request)
    {
        if (is_null(Auth::id())) {
            return redirect()->route('article.index');
        }
        $Html = (object) [
            'action'       => route('api.article.store'),
            'method'       => 'POST',
            'title'        => '',
            'keyword'      => '',
            'description'  => '',
            'content'      => '',
            'member_token' => Arr::get(Auth::user(), 'api_token'),
            'heading'      => 'Create Article',
            'success_msg'  => '新增成功',
        ];
        return view('blog.form', compact('Html'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @Author: Roy
     * @DateTime: 2021/8/13 下午 08:56
     */
    public function edit(Request $request)
    {
        if (is_null(Auth::id()) === true) {
            return redirect()->route('login');
        }
        $id = Arr::get($request, 'article');
        $article = (new ArticleService())->find($id);
        if (is_null($article) === true || Auth::id() != Arr::get($article, 'user_id')) {
            return redirect()->route('article.index');
        }
        $Html = (object) [
            'action'       => route('api.article.update', ['article' => Arr::get($article, 'id')]),
            'method'       => 'PUT',
            'title'        => Arr::get($article, 'title'),
            'content'      => Arr::get($article, 'content'),
            'keyword'      => Arr::get($article, 'seo.keyword'),
            'description'  => Arr::get($article, 'seo.description'),
            'member_token' => Arr::get(Auth::user(), 'api_token'),
            'heading'      => 'Edit Article',
            'success_msg'  => '更新成功',
        ];
        return view('blog.form', compact('Html'));
    }

    /**
     * @param  array  $Params
     *
     * @return $this
     * @Author: Roy
     * @DateTime: 2021/8/19 下午 08:22
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
        seo()->og('url', Arr::get($Params, 'url', config('meta.url')));
        seo()->og('site_name', Arr::get($Params, 'site_name', config('meta.site_name')));
        seo()->og('type', config('meta.type'));
        seo()->og('locale', config('meta.locale'));
        seo()->og('image', Arr::get($Params, 'image', config('meta.image')));
        seo()->viewport();
        seo()->csrfToken();
        return $this;
    }
}
