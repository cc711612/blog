<div class="article-like" x-data="{ animating: false }" x-init="window.addEventListener('likeAnimation', () => { animating = true; setTimeout(() => animating = false, 600); })">
    <button 
        wire:click="toggleLike"
        wire:loading.attr="disabled"
        class="btn btn-sm {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }} d-inline-flex align-items-center"
        :class="{ 'animate-heart': animating }"
        title="點讚"
    >
        <i class="fas fa-heart me-1"></i>
        <span>{{ $likeCount }}</span>
    </button>
    
    <style>
        .animate-heart {
            animation: heartBeat 0.6s ease-in-out;
        }
        
        @keyframes heartBeat {
            0% { transform: scale(1); }
            25% { transform: scale(1.3); }
            50% { transform: scale(1); }
            75% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
    </style>
</div>
