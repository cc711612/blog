<?php

namespace App\Http\Controllers\Web\Articles;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Models\Services\Web\ArticleWebService;
use App\Traits\AuthLoginTrait;
use App\Http\Presenters\Web\ArticlePresenter;
use App\Traits\SeoTrait;

class ArticleController extends BaseController
{
    use AuthLoginTrait, SeoTrait;

    /**
     * @var \App\Models\Services\Web\ArticleWebService
     */
    private $ArticleWebService;

    /**
     * @param  \App\Models\Services\Web\ArticleWebService  $ArticleWebService
     */
    public function __construct(ArticleWebService $ArticleWebService)
    {
        $this->ArticleWebService = $ArticleWebService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @Author: Roy
     * @DateTime: 2022/8/6 上午 11:37
     */
    public function index(Request $request)
    {
        $Articles = $this->ArticleWebService
            ->setRequest($request->toArray())
            ->paginate();

        $this->setSeo([
            'title'       => config('app.name'),
            'description' => '文章列表',
        ]);

        $Html = (new ArticlePresenter())
            ->setResource('Articles', $Articles)
            ->setResource('user_id', Auth::id())
            ->getIndex()
            ->all();

        return view('blog.articles.index', compact('Html'));
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
        $Article = $this->ArticleWebService->find($id);

        if (is_null($Article) === true) {
            return redirect()->route('article.index');
        }

        $Html = (new ArticlePresenter())
            ->setResource('Article', $Article)
            ->setResource('user_id', Auth::id())
            ->setResource('member_token', is_null(Auth::id()) ? null : Arr::get(Auth::user(), 'api_token'))
            ->getShow()
            ->all();

        $this->setSeo([
            'title'       => $Html->seo->title,
            'description' => $Html->seo->description,
            'keyword'     => $Html->seo->keyword,
        ]);

        return view('blog.articles.show', compact('Html'));
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

        $this->setSeo([
            'title'       => '新增文章',
            'description' => '新增文章',
        ]);

        $Html = (new ArticlePresenter())
            ->setResource('member_token', Arr::get(Auth::user(), 'api_token'))
            ->getCreate()
            ->all();

        return view('blog.articles.form', compact('Html'));
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

        $Article = $this->ArticleWebService->find($id);

        if (is_null($Article) === true || Auth::id() != Arr::get($Article, 'user_id')) {
            return redirect()->route('article.index');
        }

        $Html = (new ArticlePresenter())
            ->setResource('Article', $Article)
            ->setResource('member_token', Arr::get(Auth::user(), 'api_token'))
            ->getEdit()
            ->all();

        $this->setSeo([
            'title'       => $Html->seo->title,
            'description' => $Html->seo->description,
            'keyword'     => $Html->seo->keyword,
        ]);

        return view('blog.articles.form', compact('Html'));
    }

}
