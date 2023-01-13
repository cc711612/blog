<?php
/**
 * @Author: Roy
 * @DateTime: 2022/12/15 上午 11:21
 */

namespace App\Http\Presenters\Web;

use App\Concerns\Commons\Abstracts\PresenterAbstract;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Traits\ArticleTrait;
use App\Models\Entities\ArticleEntity;

class ArticlePresenter extends PresenterAbstract
{
    use ArticleTrait;

    public function getIndex()
    {
        $this->put('elements', $this->handleIndexArticles($this->getResource('Articles')->getCollection()));
        $this->put('page_link', $this->getResource('Articles')->links()->toHtml());
        return $this;
    }

    public function getShow()
    {
        $this->put('element', $this->handleShowArticle($this->getResource('Article')));
        $this->put('seo', $this->getShowSeo($this->getResource('Article')));
        $this->put('member_token', $this->getResource('member_token'));
        return $this;
    }

    public function getCreate()
    {
        $Puts = [
            'action'       => route('api.article.store'),
            'method'       => 'POST',
            'title'        => '',
            'keyword'      => '',
            'description'  => '',
            'content'      => '',
            'member_token' => $this->getResource('member_token'),
            'heading'      => 'Create Article',
            'success_msg'  => '新增成功',
        ];

        foreach ($Puts as $key => $put) {
            $this->put($key, $put);
        }
        return $this;
    }

    public function getEdit()
    {
        $Article = $this->getResource('Article');
        $Puts = [
            'action'       => route('api.article.update', ['article' => Arr::get($Article, 'id')]),
            'method'       => 'PUT',
            'title'        => Arr::get($Article, 'title'),
            'content'      => Arr::get($Article, 'content'),
            'keyword'      => Arr::get($Article, 'seo.keyword'),
            'description'  => Arr::get($Article, 'seo.description'),
            'member_token' => $this->getResource('member_token'),
            'heading'      => 'Edit Article',
            'success_msg'  => '更新成功',
        ];
        foreach ($Puts as $key => $put) {
            $this->put($key, $put);
        }
        $this->put('seo', $this->getShowSeo($Article));

        return $this;
    }

    private function handleShowArticle(ArticleEntity $Article)
    {
        return [
            'id'         => Arr::get($Article, 'id'),
            'title'      => Arr::get($Article, 'title'),
            'content'    => Arr::get($Article, 'content'),
            'sub_title'  => $this->getShortContent(strip_tags(Arr::get($Article, 'content')), 60, '...'),
            'user_name'  => Arr::get($Article, 'users.name'),
            'updated_at' => Arr::get($Article, 'updated_at')->format('Y-m-d H:i:s'),
            'actions'    => (object) [
                'show_uri'   => route('article.show', ['article' => Arr::get($Article, 'id')]),
                'edit_uri'   => route('article.edit', ['article' => Arr::get($Article, 'id')]),
                'delete_uri' => route('api.article.destroy', ['article' => Arr::get($Article, 'id')]),
                'user_uri'   => route('article.index', ['user' => Arr::get($Article, 'user_id')]),
            ],
        ];
    }

    private function handleIndexArticles(Collection $Articles)
    {
        return $Articles->map(function ($Article) {
            return [
                'id'         => Arr::get($Article, 'id'),
                'title'      => Arr::get($Article, 'title'),
                'content'    => Arr::get($Article, 'content'),
                'sub_title'  => $this->getShortContent(strip_tags(Arr::get($Article, 'content')), 65, '...'),
                'user_name'  => Arr::get($Article, 'users.name'),
                'updated_at' => Arr::get($Article, 'updated_at')->format('Y-m-d H:i:s'),
                'created_at' => Arr::get($Article, 'created_at')->format('Y-m-d H:i:s'),
                'actions'    => (object) [
                    'show_uri'   => route('article.show', ['article' => Arr::get($Article, 'id')]),
                    'edit_uri'   => route('article.edit', ['article' => Arr::get($Article, 'id')]),
                    'delete_uri' => route('api.article.destroy', ['article' => Arr::get($Article, 'id')]),
                    'user_uri'   => route('article.index', ['user' => Arr::get($Article, 'user_id')]),
                ],
                'is_editor'  => $this->getResource('user_id') == Arr::get($Article, 'user_id'),
            ];
        });
    }

    private function getShowSeo(ArticleEntity $Article)
    {
        return [
            'title'       => Arr::get($Article, 'title'),
            'description' => is_null(Arr::get($Article, 'seo.description'))
                ? preg_replace('/\s(?=)/', '',
                    $this->getShortContent(strip_tags(Arr::get($Article, 'content')), 150))
                : Arr::get($Article, 'seo.description'),
            'keyword'     => Arr::get($Article, 'seo.keyword'),
        ];
    }
}
