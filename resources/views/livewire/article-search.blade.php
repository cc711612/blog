<div>
    <!-- 搜尋區域 -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="form-group">
                <div class="input-group">
                    <input 
                        type="text" 
                        class="form-control" 
                        placeholder="搜尋文章..." 
                        wire:model.debounce.300ms="search"
                        wire:loading.attr="disabled"
                    >
                    <button 
                        class="btn btn-outline-secondary" 
                        type="button" 
                        wire:click="clearSearch"
                        wire:loading.attr="disabled"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @if(!empty($search))
                    <small class="text-muted">
                        搜尋 "{{ $search }}" 的結果
                    </small>
                @endif
            </div>
        </div>
    </div>

    <!-- 載入中指示器 -->
    <div wire:loading wire:target="search" class="text-center mb-3">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">載入中...</span>
        </div>
        <span class="ms-2">搜尋中...</span>
    </div>

    <!-- 文章列表 -->
    <div class="article-list">
        @if(empty($articles))
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">
                    @if(empty($search))
                        沒有文章
                    @else
                        找不到符合 "{{ $search }}" 的文章
                    @endif
                </h5>
            </div>
        @else
            @foreach($articles as $article)
                <div class="post-preview">
                    <a href="{{ route('article.show', ['article' => $article->id]) }}">
                        <h2 class="post-title">{{ $article->title }}</h2>
                        <h3 class="post-subtitle">{{ substr(strip_tags($article->content), 0, 100) }}...</h3>
                    </a>
                    <p class="post-meta">
                        Posted by
                        <a href="#">{{ $article->users->name ?? 'Anonymous' }}</a>
                        on {{ $article->created_at->format('Y-m-d') }}
                        <span class="ms-3">
                            <i class="fas fa-comments"></i> 
                            {{ $article->comments_count ?? 0 }} 則留言
                        </span>
                    </p>
                </div>
                <hr class="my-4"/>
            @endforeach
        @endif
    </div>

    <!-- 載入更多按鈕 -->
    @if($hasMorePages)
        <div class="text-center mt-4">
            <button 
                class="btn btn-outline-primary" 
                wire:click="loadMore"
                wire:loading.attr="disabled"
                wire:loading.class="btn-primary"
            >
                <span wire:loading.remove>載入更多文章</span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    載入中...
                </span>
            </button>
        </div>
    @endif

    <!-- 搜尋結果統計 -->
    @if(!empty($search) && !empty($articles))
        <div class="text-center mt-3">
            <small class="text-muted">
                找到 {{ count($articles) }} 篇文章
                @if($hasMorePages)
                    (還有更多)
                @endif
            </small>
        </div>
    @endif
</div>
