<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use App\Models\Services\CommentService;
use App\Models\Entities\CommentEntity;
use Carbon\Carbon;

class Comment extends Component
{
    // 常數定義
    const CACHE_DURATION = 86400; // 24小時
    const COOKIE_DURATION = 30 * 86400; // 30天
    const VALID_VOTE_TYPES = ['up', 'down'];
    const MAX_SEARCH_LENGTH = 100;
    const SEARCH_RATE_LIMIT = 60; // 每分鐘最多 60 次搜尋

    public $member_token;
    public $article_id;
    public $store_uri;
    public $content;
    public $search = '';
    public $sortOrder = 'newest'; // newest, oldest, popular

    // 表單內容的驗證規則
    protected $rules = [
        'content' => ['required', 'min:2', 'max:400'],
        'search' => ['nullable', 'string', 'max:' . self::MAX_SEARCH_LENGTH],
    ];

    // 驗證失敗的錯誤訊息
    protected $messages = [
        'content.required' => '請填寫回覆內容',
        'content.min'      => '回覆內容至少 2 個字元',
        'content.max'      => '回覆內容至多 400 個字元',
    ];

    public function render()
    {
        $comments = app(CommentService::class)
            ->setRequest(['id' => $this->article_id])
            ->getCommentsByArticleId();

        // 搜尋功能
        if (!empty($this->search)) {
            $this->sanitizeSearch();
            $this->checkSearchRateLimit();
            
            $comments = $comments->filter(function ($comment) {
                $safeSearch = $this->search;
                return stripos($comment->content, $safeSearch) !== false ||
                       stripos($comment->users->name ?? '', $safeSearch) !== false;
            });
        }

        // 排序功能
        switch ($this->sortOrder) {
            case 'oldest':
                $comments = $comments->sortBy('created_at');
                break;
            case 'popular':
                $comments = $comments->sortByDesc(function ($comment) {
                    return $this->getCommentVotes($comment->id)['upvotes'] - $this->getCommentVotes($comment->id)['downvotes'];
                });
                break;
            case 'newest':
            default:
                $comments = $comments->sortByDesc('created_at');
                break;
        }

        return view('livewire.comment', [
            'comments' => $comments->values(),
        ]);
    }

    public function mount($element)
    {
        $this->member_token = is_null(Auth::id()) ? null : Arr::get(Auth::user(), 'api_token');
        $this->article_id = $element->id;
        $this->store_uri = url('/api/comment');
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

    public function updatedSearch()
    {
        $this->validateOnly('search');
        $this->sanitizeSearch();
        // 搜尋時自動重新渲染
    }

    public function updatedSortOrder()
    {
        // 排序時自動重新渲染
    }

    // 投票功能
    public function voteComment($commentId, $voteType)
    {
        // 驗證投票類型
        if (!in_array($voteType, self::VALID_VOTE_TYPES)) {
            throw new \InvalidArgumentException('Invalid vote type. Must be "up" or "down".');
        }

        $cacheKey = "comment_votes:{$commentId}";
        $cookieName = "comment_vote_{$commentId}";
        
        // 生成唯一的用戶標識
        $userIdentifier = $this->getUserIdentifier();
        $votes = Cache::get($cacheKey, []);

        // 檢查當前投票狀態
        $currentVote = null;
        foreach ($votes as $vote) {
            if ($vote['user_id'] == $userIdentifier) {
                $currentVote = $vote['type'];
                break;
            }
        }

        // 移除之前的投票（如果存在）
        $votes = array_filter($votes, function ($vote) use ($userIdentifier) {
            return $vote['user_id'] != $userIdentifier;
        });

        // 如果是相同投票，則取消投票
        if ($currentVote === $voteType) {
            // 取消投票，不添加新投票
            Cookie::queue($cookieName, '', -1);
        } else {
            // 添加新投票或更改投票
            $votes[] = [
                'user_id' => $userIdentifier,
                'type' => $voteType, // 'up' or 'down'
                'created_at' => Carbon::now()->timestamp
            ];
            
            // 設置 Cookie，有效期 30 天
            Cookie::queue($cookieName, $voteType, self::COOKIE_DURATION / 86400);
        }

        Cache::put($cacheKey, array_values($votes), self::CACHE_DURATION);
    }

    public function getCommentVotes($commentId)
    {
        $cacheKey = "comment_votes:{$commentId}";
        $cookieName = "comment_vote_{$commentId}";
        $votes = Cache::get($cacheKey, []);

        $upvotes = 0;
        $downvotes = 0;
        $userVote = null;

        // 生成唯一的用戶標識
        $userIdentifier = $this->getUserIdentifier();

        foreach ($votes as $vote) {
            if ($vote['type'] === 'up') {
                $upvotes++;
            } else {
                $downvotes++;
            }

            if ($vote['user_id'] == $userIdentifier) {
                $userVote = $vote['type'];
            }
        }

        // 如果快取中沒有找到用戶投票，檢查 Cookie
        if ($userVote === null) {
            $cookieVote = request()->cookie($cookieName);
            if ($cookieVote && in_array($cookieVote, self::VALID_VOTE_TYPES)) {
                $userVote = $cookieVote;
            }
        }

        return [
            'upvotes' => $upvotes,
            'downvotes' => $downvotes,
            'user_vote' => $userVote,
            'total' => $upvotes - $downvotes
        ];
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    /**
     * 生成唯一的用戶標識（基於 IP 和 User-Agent）
     * 
     * @return string
     */
    private function getUserIdentifier()
    {
        return md5(request()->ip() . request()->userAgent());
    }

    /**
     * 清理搜尋字串，防止 XSS
     */
    private function sanitizeSearch()
    {
        if (!empty($this->search)) {
            // 移除 HTML 標籤和特殊字元
            $this->search = strip_tags($this->search);
            $this->search = mb_substr($this->search, 0, self::MAX_SEARCH_LENGTH);
            
            // 移除潛在危險字元
            $this->search = preg_replace('/[<>"\']/', '', $this->search);
        }
    }

    /**
     * 檢查搜尋頻率限制
     */
    private function checkSearchRateLimit()
    {
        $key = 'comment_search_rate_limit:' . request()->ip();
        $count = Cache::get($key, 0);
        
        if ($count >= self::SEARCH_RATE_LIMIT) {
            // 記錄濫用行為
            \Log::warning('Comment search rate limit exceeded', [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'search_term' => $this->search,
                'article_id' => $this->article_id
            ]);
            
            // 清空搜尋以防止濫用
            $this->search = '';
            return false;
        }
        
        // 增加計數器，1分鐘過期
        Cache::put($key, $count + 1, 60);
        return true;
    }
}
