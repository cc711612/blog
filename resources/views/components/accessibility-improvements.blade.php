{{-- 無障礙改善組件 --}}

{{-- 改善的按鈕組件 --}}
@component('components.accessible-button', [
    'onClick' => $onClick ?? '',
    'label' => $label ?? 'Button',
    'class' => $class ?? 'btn btn-primary',
    'disabled' => $disabled ?? false
])
    <button 
        class="{{ $class }}"
        @if($onClick) wire:click="{{ $onClick }}" @endif
        @if($disabled) disabled @endif
        aria-label="{{ $label }}"
        {{ $disabled ? 'aria-disabled="true"' : '' }}
        type="button"
    >
        {{ $slot }}
    </button>
@endcomponent

{{-- 改善的連結組件 --}}
@component('components.accessible-link', [
    'href' => $href,
    'label' => $label ?? '',
    'class' => $class ?? ''
])
    <a 
        href="{{ $href }}"
        class="{{ $class }}"
        @if($label) aria-label="{{ $label }}" @endif
    >
        {{ $slot }}
    </a>
@endcomponent

{{-- 改善的圖片組件 --}}
@component('components.accessible-image', [
    'src' => $src,
    'alt' => $alt,
    'class' => $class ?? '',
    'loading' => $loading ?? 'lazy'
])
    <img 
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="{{ $class }}"
        loading="{{ $loading }}"
        @if($loading === 'lazy')
            data-src="{{ $src }}"
            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
            class="lazy"
        @endif
    >
@endcomponent

{{-- 改善的表單輸入組件 --}}
@component('components.accessible-input', [
    'name' => $name,
    'label' => $label,
    'type' => $type ?? 'text',
    'value' => $value ?? '',
    'required' => $required ?? false,
    'error' => $error ?? null
])
    <div class="form-group">
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required) <span class="text-danger">*</span> @endif
        </label>
        <input 
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value }}"
            class="form-control @error($name) is-invalid @enderror"
            @if($required) required @endif
            aria-describedby="{{ $name }}-help {{ $name }}-error"
            aria-invalid="{{ $error ? 'true' : 'false' }}"
        >
        <small id="{{ $name }}-help" class="form-text text-muted">
            {{ $help ?? '' }}
        </small>
        @error($name)
            <div id="{{ $name }}-error" class="invalid-feedback" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
@endcomponent

{{-- 主要地標改善 --}}
<main role="main" aria-label="主要內容">
    {{ $mainContent ?? '' }}
</main>

{{-- 跳過連結 (對於螢幕閱讀器) }}
<a href="#main-content" class="skip-link" aria-label="跳過到主要內容">
    跳過到主要內容
</a>

<style>
/* 無障礙改善樣式 */
.skip-link {
    position: absolute;
    top: -40px;
    left: 6px;
    background: #000;
    color: #fff;
    padding: 8px;
    text-decoration: none;
    z-index: 1000;
}

.skip-link:focus {
    top: 6px;
}

/* 改善對比度 */
.high-contrast {
    background: #000 !important;
    color: #fff !important;
}

.high-contrast .btn {
    background: #fff !important;
    color: #000 !important;
    border: 2px solid #fff !important;
}

/* 焦點指示器改善 */
.btn:focus,
input:focus,
textarea:focus,
select:focus {
    outline: 3px solid #005fcc;
    outline-offset: 2px;
}

/* 延遲載入圖片過渡 */
.lazy {
    transition: opacity 0.3s;
}

.lazy.loaded {
    opacity: 1;
}
</style>

<script>
// 無障礙功能增強
document.addEventListener('DOMContentLoaded', function() {
    // 鍵盤導航改善
    const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
    
    // 陷阱焦點在模態框內
    function trapFocus(element) {
        const focusable = element.querySelectorAll(focusableElements);
        const firstFocusable = focusable[0];
        const lastFocusable = focusable[focusable.length - 1];
        
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusable) {
                        lastFocusable.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastFocusable) {
                        firstFocusable.focus();
                        e.preventDefault();
                    }
                }
            }
        });
    }
    
    // 宣告即時內容變更
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;
        
        document.body.appendChild(announcement);
        
        setTimeout(function() {
            document.body.removeChild(announcement);
        }, 1000);
    }
    
    // 為動態內容添加 ARIA 標籤
    function addAriaToDynamicContent() {
        const dynamicRegions = document.querySelectorAll('[data-dynamic="true"]');
        dynamicRegions.forEach(region => {
            if (!region.getAttribute('aria-live')) {
                region.setAttribute('aria-live', 'polite');
            }
        });
    }
    
    addAriaToDynamicContent();
    
    // 監聽 Livewire 更新
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('message.processed', (message, component) => {
            addAriaToDynamicContent();
            announceToScreenReader('內容已更新');
        });
    }
});
</script>
