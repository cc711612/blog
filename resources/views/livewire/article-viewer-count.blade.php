<div class="article-viewer-count d-inline-flex align-items-center">
    <div class="d-inline-flex align-items-center article-viewer-wrap">
        <span class="badge bg-success article-viewer-badge">
            <i class="fas fa-eye"></i>
            {{ $viewerCount }}
        </span>
        <small class="article-viewer-text">
            @if($viewerCount == 0)
                目前無人瀏覽
            @elseif($viewerCount == 1)
                目前 1 人正在閱讀
            @else
                目前 {{ $viewerCount }} 人正在閱讀
            @endif
        </small>
    </div>
    
    <!-- 心跳檢測 - 每30秒更新一次 -->
    <script>
        document.addEventListener('livewire:init', () => {
            setInterval(() => {
                @this.heartbeat();
            }, 30000);
        });
    </script>
</div>
