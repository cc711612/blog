<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Models\Services\CommentService;
use App\Models\Entities\CommentEntity;

class Comment extends Component
{
    public $member_token;
    public $article_id;
    public $store_uri;
    public $content;

    // 表單內容的驗證規則
    protected $rules = [
        'content' => ['required', 'min:2', 'max:400'],
    ];

    // 驗證失敗的錯誤訊息
    protected $messages = [
        'content.required' => '請填寫回覆內容',
        'content.min'      => '回覆內容至少 2 個字元',
        'content.max'      => '回覆內容至多 400 個字元',
    ];

    public function render()
    {
        return view('livewire.comment', [
            'comments' => app(CommentService::class)
                ->setRequest(['id' => $this->article_id])
                ->getCommentsByArticleId(),
        ]);
    }

    public function mount($element)
    {
        $this->member_token = is_null(Auth::id()) ? null : Arr::get(Auth::user(), 'api_token');
        $this->article_id = $element->id;
        $this->store_uri = route('api.article.store', [
            'article_id' => $element->id,
        ]);
    }

    public function store()
    {
        $ValidateData = $this->validate();
        app(CommentService::class)
            ->setRequest([
                CommentEntity::Table => [
                    'article_id' => $this->article_id,
                    'user_id'    => Auth::id(),
                    'content'    => Arr::get($ValidateData,'content'),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'status'     => 1,
                ],
            ])
            ->createComment();
        $this->content = null;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
