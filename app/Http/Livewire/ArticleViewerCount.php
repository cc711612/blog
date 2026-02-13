<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ArticleViewerCount extends Component
{
    public $articleId;
    public $viewerCount = 0;
    public $isOnline = false;

    protected $listeners = ['userJoined', 'userLeft'];

    public function mount($articleId)
    {
        $this->articleId = $articleId;
        $this->addViewer();
        $this->updateViewerCount();
    }

    public function addViewer()
    {
        $sessionId = Session::getId();
        $key = "article_viewers:{$this->articleId}";
        
        // 簡化為只使用 Cache
        $viewers = Cache::get($key, []);
        $viewers[$sessionId] = Carbon::now()->timestamp;
        Cache::put($key, $viewers, 300); // 5分鐘過期
    }

    public function removeViewer()
    {
        $sessionId = Session::getId();
        $key = "article_viewers:{$this->articleId}";
        
        $viewers = Cache::get($key, []);
        unset($viewers[$sessionId]);
        Cache::put($key, $viewers, 300);
    }

    public function updateViewerCount()
    {
        $key = "article_viewers:{$this->articleId}";
        
        $viewers = Cache::get($key, []);
        // 清理過期的瀏覽者 (5分鐘未活動)
        $now = Carbon::now()->timestamp;
        $activeViewers = array_filter($viewers, function ($timestamp) use ($now) {
            return ($now - $timestamp) < 300;
        });
        Cache::put($key, $activeViewers, 300);
        $this->viewerCount = count($activeViewers);
    }

    public function userJoined($articleId)
    {
        if ($articleId === $this->articleId) {
            $this->updateViewerCount();
        }
    }

    public function userLeft($articleId)
    {
        if ($articleId === $this->articleId) {
            $this->updateViewerCount();
        }
    }

    public function heartbeat()
    {
        $sessionId = Session::getId();
        $key = "article_viewers:{$this->articleId}";
        
        $viewers = Cache::get($key, []);
        $viewers[$sessionId] = Carbon::now()->timestamp;
        Cache::put($key, $viewers, 300);
        
        $this->updateViewerCount();
    }

    public function render()
    {
        // 每30秒更新一次計數
        $this->updateViewerCount();
        
        return view('livewire.article-viewer-count');
    }

    // 組件銷毀時移除瀏覽者
    public function dehydrate()
    {
        $this->removeViewer();
    }
}
