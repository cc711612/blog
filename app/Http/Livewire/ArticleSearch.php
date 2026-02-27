<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Entities\ArticleEntity;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleSearch extends Component
{
    public $search = '';
    public $perPage = 10;
    public $articles;
    public $hasMorePages = false;
    public $currentPage = 1;
    public $lastPage = 1;

    // 安全設定常數
    const MAX_SEARCH_LENGTH = 100;
 const SEARCH_RATE_LIMIT = 30; // 每分鐘最多 30 次搜尋

    protected $listeners = ['refreshArticles' => '$refresh'];

    // 搜尋驗證規則
    protected $rules = [
        'search' => ['nullable', 'string', 'max:' . self::MAX_SEARCH_LENGTH],
    ];

    public function mount()
    {
        // 從 URL 參數讀取搜尋關鍵字
        $this->search = request()->get('search', '');
        $this->currentPage = max(1, (int) request()->get('page', 1));
        $this->loadArticles();
    }

    public function updatedSearch()
    {
        $this->validateOnly('search');
        $this->sanitizeSearch();
        
        // 如果搜尋字串太短，顯示所有文章而不是空列表
        if (strlen($this->search) < 2 && !empty($this->search)) {
            $this->resetPage();
            $this->loadAllArticles();
            return;
        }
        
        $this->checkRateLimit();
        $this->resetPage();
        $this->loadArticles();
    }

    public function loadArticles()
    {
        $query = ArticleEntity::with(['users' => function ($query) {
                $query->select(['id', 'name', 'images']);
            }])
            ->where('status', 1)
            ->where('user_id', config('app.user_id'));

        if (!empty($this->search)) {
            // 使用參數化查詢防止 SQL Injection
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('content', 'like', $searchTerm);
            });
            
            // 記錄搜尋日誌
            $this->logSearch();
        }

        $articles = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $this->articles = $articles->items();
        $this->currentPage = $articles->currentPage();
        $this->lastPage = $articles->lastPage();
        $this->hasMorePages = $articles->hasMorePages();
    }

    /**
     * 載入所有文章（當搜尋字串太短時使用）
     */
    public function loadAllArticles()
    {
        $this->loadArticles();
    }

    public function loadMore()
    {
        $this->perPage += 5;
        $this->loadArticles();
    }

    public function resetPage()
    {
        $this->perPage = 10;
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
        $this->loadArticles();
    }

    /**
     * 清理搜尋字串，防止 XSS
     */
    private function sanitizeSearch()
    {
        if (!empty($this->search)) {
            // 移除 HTML 標籤和特殊字元
            $this->search = strip_tags($this->search);
            $this->search = Str::limit($this->search, self::MAX_SEARCH_LENGTH);
            
            // 移除潛在危險字元
            $this->search = preg_replace('/[<>"\']/', '', $this->search);
        }
    }

    /**
     * 檢查搜尋頻率限制
     */
    private function checkRateLimit()
    {
        $key = 'search_rate_limit:' . request()->ip();
        $count = Cache::get($key, 0);
        
        if ($count >= self::SEARCH_RATE_LIMIT) {
            // 記錄濫用行為
            Log::warning('Search rate limit exceeded', [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'search_term' => $this->search
            ]);
            
            // 拋出例外或返回錯誤
            throw new \Exception('搜尋頻率過高，請稍後再試');
        }
        
        // 增加計數器，1分鐘過期
        Cache::put($key, $count + 1, 60);
    }

    /**
     * 記錄搜尋日誌
     */
    private function logSearch()
    {
        Log::info('Article search performed', [
            'search_term' => $this->search,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'results_count' => count($this->articles ?? [])
        ]);
    }

    public function render()
    {
        return view('livewire.article-search');
    }
}
