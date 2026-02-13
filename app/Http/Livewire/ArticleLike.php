<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class ArticleLike extends Component
{
    // 常數定義
    const CACHE_DURATION = 86400; // 24小時
    const COOKIE_DURATION = 30 * 86400; // 30天

    public $articleId;
    public $likeCount = 0;
    public $isLiked = false;
    public $isLoggedIn = false;

    public function mount($articleId)
    {
        $this->articleId = $articleId;
        $this->loadLikeStatus();
    }

    public function loadLikeStatus()
    {
        $cacheKey = "article_likes:{$this->articleId}";
        $likes = Cache::get($cacheKey, []);
        
        $this->likeCount = count($likes);
        
        // 使用 Laravel Cookie helper 檢查用戶是否已點讚
        $cookieName = "article_liked_{$this->articleId}";
        $this->isLiked = request()->cookie($cookieName) === '1';
        
        // 移除登入檢查，所有人都可以點讚
        $this->isLoggedIn = true;
    }

    public function toggleLike()
    {
        // 除錯資訊
        if (config('app.debug')) {
            \Log::info('ArticleLike: toggleLike called', [
                'articleId' => $this->articleId,
                'currentLikeCount' => $this->likeCount,
                'isLiked' => $this->isLiked
            ]);
        }

        $cacheKey = "article_likes:{$this->articleId}";
        $cookieName = "article_liked_{$this->articleId}";
        
        // 生成唯一的用戶標識
        $userIdentifier = $this->getUserIdentifier();
        $likes = Cache::get($cacheKey, []);

        if ($this->isLiked) {
            // 取消讚 - 從快取中移除用戶標識
            $likes = array_diff($likes, [$userIdentifier]);
            $this->isLiked = false;
            $this->likeCount--;
            
            // 刪除 Cookie
            Cookie::queue($cookieName, '', -1);
        } else {
            // 按讚 - 添加用戶標識到快取
            $likes[] = $userIdentifier;
            $this->isLiked = true;
            $this->likeCount++;
            
            // 設置 Cookie，有效期 30 天
            Cookie::queue($cookieName, '1', self::COOKIE_DURATION / 86400);
            
            // 觸發動畫效果
            $this->dispatchBrowserEvent('likeAnimation');
        }

        // 更新快取
        Cache::put($cacheKey, array_values(array_unique($likes)), self::CACHE_DURATION);

        // 除錯資訊
        if (config('app.debug')) {
            \Log::info('ArticleLike: like updated', [
                'newLikeCount' => $this->likeCount,
                'newIsLiked' => $this->isLiked,
                'totalLikes' => count($likes),
                'userIdentifier' => $userIdentifier
            ]);
        }
    }

    private function syncToDatabase($likes)
    {
        // 這裡可以將點讚資料同步到資料庫
        // 例如：article_likes 表或 articles 表的 likes_count 欄位
        // 目前先使用快取，避免頻繁的資料庫操作
    }

    public function render()
    {
        return view('livewire.article-like');
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
}
