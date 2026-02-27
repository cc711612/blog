<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <!--
                             _oo0oo_
                            o8888888o
                            88" . "88
                            (| -_- |)
                            0\  =  /0
                        ___/`---'\___
                        .' \\|     |// '.
                        / \\|||  :  |||// \
                    / _||||| -:- |||||- \
                    |   | \\\  -  /// |   |
                    | \_|  ''\---/''  |_/ |
                    \  .-\__  '-'  ___/-. /
                    ___'. .'  /--.--\  `. .'___
                ."" '<  `.___\_<|>_/___.' >' "".
                | | :  `- \`.;`\ _ /`;.`/ - ` : | |
                \  \ `_.   \_ __\ /__ _/   .-` /  /
            =====`-.____`.___ \_____/___.-`___.-'=====
                            `=---='
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                    佛祖保佑         永無bug
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    -->
    {{ seo()->render() }}
    <link rel="icon" type="image/x-icon" href="{{url('/favicon.ico')}}"/>

    <!-- Preconnect to critical third-party origins（jsdelivr 無第三方 Cookie） -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Bootstrap 5 CSS：preload 非同步，消除 render-blocking -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'" crossorigin="anonymous">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" crossorigin="anonymous"></noscript>
    <!-- FontAwesome CSS: jsdelivr 無第三方 Cookie，preload 非同步載入 -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'" crossorigin="anonymous">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" crossorigin="anonymous"></noscript>

    <!-- GA / GTM preconnect（節省約 300ms DNS+連線時間）— 不加 crossorigin，Script 非 CORS 資源 -->
    <link rel="preconnect" href="https://www.google-analytics.com">
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    
    <!-- Critical CSS inline（含 custom-theme，消除 render-blocking） -->
    <style>
        /* ===== custom-theme.css inlined ===== */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #e67e22;
            --light-bg: #f8f9fa;
            --text-muted: #6c757d;
            --border-color: #dee2e6;
        }
        .masthead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            padding: 4rem 0 2rem !important;
            color: white;
            text-align: center;
        }
        .site-heading h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 1rem;
        }
        .site-heading .subheading {
            font-size: 1.3rem;
            font-weight: 300;
            opacity: 0.9;
        }
        .post-preview {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
        }
        .post-preview:hover { transform: translateY(-3px); box-shadow: 0 5px 25px rgba(0,0,0,0.15); }
        .post-preview:last-child { border-bottom: none; }
        .post-preview + hr { display: none; }
        .post-title { font-size: 1.8rem; font-weight: 700; color: #2c3e50; margin-bottom: 0.5rem; line-height: 1.3; }
        .post-subtitle { font-size: 1.1rem; color: #6c757d; line-height: 1.6; margin-bottom: 1.5rem; }
        .post-meta { font-size: 0.9rem; color: #6c757d; border-top: 1px solid #dee2e6; padding-top: 1rem; margin-top: 1.5rem; }
        .post-meta a { color: #b5520a; text-decoration: none; font-weight: 600; }
        .post-meta a:hover { text-decoration: underline; }
        .container { max-width: 900px; }
        .form-control { border-radius: 8px; border: 2px solid #dee2e6; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease; }
        .form-control:focus { border-color: #e67e22; box-shadow: 0 0 0 0.2rem rgba(230,126,34,0.25); }
        .btn-outline-secondary { border-radius: 8px; border-color: #dee2e6; color: #6c757d; }
        .btn-outline-secondary:hover { background-color: #e67e22; border-color: #e67e22; color: white; }
        .search-clear-btn { width: 3rem; min-width: 3rem; padding-left: 0; padding-right: 0; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 3rem; }
        .btn-outline-primary { border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; border: 2px solid #006f87 !important; color: #006f87 !important; --bs-btn-color: #006f87; --bs-btn-border-color: #006f87; transition: all 0.3s ease; }
        .btn-outline-primary:hover { background-color: #006f87 !important; border-color: #006f87 !important; color: #fff !important; --bs-btn-hover-bg: #006f87; --bs-btn-hover-border-color: #006f87; transform: translateY(-2px); }
        .load-more-btn { min-width: 11rem; }
        .number-of-people { position: fixed; bottom: 20px; right: 20px; z-index: 1000; }
        .badge { background: #e67e22; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; box-shadow: 0 2px 10px rgba(230,126,34,0.3); }
        body { background-color: #f8f9fa; }
        .article-content pre {
            box-sizing: border-box;
            background: #2f333d;
            color: #cdd3de;
            font-family: Menlo, Monaco, monospace;
            line-height: 1.6;
            margin: 1.2em 0;
            overflow: auto;
            padding: 12px 15px;
            border: 1px solid #292c33;
            border-radius: 6px;
            width: 100%;
        }
        .article-content pre code {
            display: block;
            font-family: inherit;
            font-size: 14px;
            white-space: pre;
            color: inherit;
            background: transparent;
            padding: 0;
        }
        .article-content :not(pre) > code {
            background: rgba(47, 51, 61, 0.08);
            border: 1px solid rgba(47, 51, 61, 0.15);
            border-radius: 4px;
            color: #2f333d;
            font-family: Menlo, Monaco, monospace;
            font-size: 0.92em;
            padding: 0.12em 0.38em;
        }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @media (max-width: 768px) {
            .masthead { padding: 3rem 0 1.5rem !important; }
            .site-heading h1 { font-size: 2.5rem; }
            .post-preview { padding: 1.5rem; }
            .post-title { font-size: 1.5rem; }
            .article-content pre { padding: 10px 12px; font-size: 13px; }
        }
        /* ===== FontAwesome font-display:swap override（覆蓋 CDN CSS 無 font-display 的問題） ===== */
        @font-face {
            font-family: 'Font Awesome 5 Free';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url('https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/webfonts/fa-solid-900.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Font Awesome 5 Free';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/webfonts/fa-regular-400.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Font Awesome 5 Brands';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/webfonts/fa-brands-400.woff2') format('woff2');
        }
    </style>
    
    <!-- Non-critical CSS: 非同步延後載入 -->
    <link rel="preload" href="{{url('/css/main.css?v='.config('app.version'))}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{url('/css/main.css?v='.config('app.version'))}}"></noscript>
    
    <link rel="preload" href="{{url('/css/badge.css?v='.config('app.version'))}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{url('/css/badge.css?v='.config('app.version'))}}"></noscript>
    
    <link rel="preload" href="{{url('/css/desktop-optimized.css?v='.config('app.version'))}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{url('/css/desktop-optimized.css?v='.config('app.version'))}}"></noscript>
    
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    @stack('css-plugins')
    @livewireStyles
    <!-- END PAGE LEVEL PLUGINS -->
    @if(config('app.env') !== 'local')
        @include("layouts.tracking_header")
    @endif
    @laravelPWA
    
    <!-- Performance Optimization -->
    <script>
    // CSS 非同步載入
    function loadCSS(href) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        document.head.appendChild(link);
    }
    
    // 動態載入 JavaScript - 解決 Render Blocking
    function loadScript(src, async = true, defer = true) {
        const script = document.createElement('script');
        script.src = src;
        if (async) script.async = true;
        if (defer) script.defer = true;
        document.head.appendChild(script);
    }
    
    // 等待關鍵 CSS 載入後再載入其他資源
    window.addEventListener('load', function() {
        // 預載入字體
        if ('fonts' in document) {
            document.fonts.load('400 1em Lora').then(function() {
                document.documentElement.classList.add('fonts-loaded');
            });
        }
        
        // 預載入關鍵圖片已移除
    });
    
    // 圖片延遲載入 - 改善圖片載入性能
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('img[data-src]');
            lazyImages.forEach(img => imageObserver.observe(img));
        });
    }
    
    // Service Worker 註冊 - 改善快取策略
    if ('serviceWorker' in navigator && @json(config('app.env')) === 'production') {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js?v=@json(config("app.version"))')
                .then(function(registration) {
                    console.log('SW registered: ', registration);
                })
                .catch(function(registrationError) {
                    console.log('SW registration failed: ', registrationError);
                });
        });
    }
    </script>
</head>
<body>
@include("layouts.tracking_noscript")
<!-- Messenger 洽談外掛程式 Code -->
<div id="fb-root"></div>
<!-- Your 洽談外掛程式 code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>
<div class="loader" id="loading" style="display:none;">
</div>
<!-- Navigation-->
@include("layouts.header")
<div class="number-of-people">
    <div class="badge" id="online">
        0
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="icons" viewBox="0 0 48 48">
        <defs>
            <linearGradient id="New_Gradient_Swatch_5" x1="24" y1="47.52" x2="24" y2="0.48"
                            gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#fd7d47"/>
                <stop offset="1" stop-color="#ff5099"/>
            </linearGradient>
        </defs>
        <title>user-people-family-house-home</title>
        <path id="user-people-family-house-home"
              d="M48,24.48l-8.74-8.74h.12V7.54h-4.1v4.22L24,.48l-24,24,2.9,2.9,1.62-1.63V47.52h39V25.75l1.62,1.63ZM39.38,43.42H8.62V21.65L24,6.28,39.38,21.65ZM26.05,29.07a4.1,4.1,0,0,1,4.1,4.1v6.15H17.85V33.17a4.1,4.1,0,0,1,4.1-4.1ZM24,18.82a4.1,4.1,0,1,1-4.1,4.1A4.1,4.1,0,0,1,24,18.82Z"
              fill="white"/>
    </svg>
</div>
<main id="main-content">
@yield("content")
</main>
<!-- Footer-->
@include("layouts.footer")
<script>
    let logout_uri = @json(route('logout'));
    // 線上人數
    let onlineUsers = 0;
</script>
@livewireScripts
@stack('scripts')
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" defer></script>
<!-- Core theme JS - 延後載入 -->
<script>
    window.addEventListener('load', function() {
        // global.js 必須最先載入（定義 window._csrfToken 供後續 JS 使用）
        // 用 onload 串行確保依賴順序，避免 $ / _csrfToken 未定義錯誤
        var globalScript = document.createElement('script');
        globalScript.src = "{{url('/js/blog/global.js?v='.config('app.version'))}}";
        globalScript.onload = function() {
            // global.js 載入完成後，再並行載入其他非關鍵 JS
            loadScript("{{url('/js/blog/logout.js?v='.config('app.version'))}}");
            loadScript("{{url('/js/blog/scripts.js?v='.config('app.version'))}}");
            loadScript("{{url('js/app.js?v='.config('app.version'))}}");
            loadScript("{{url('/js/blog/online.js?v='.config('app.version'))}}");
            
            // 性能監控
            @if(config('app.env') === 'production')
            loadScript("{{url('/js/performance-monitor.js')}}");
            @endif
        };
        document.head.appendChild(globalScript);
        
        // 第三方腳本最後載入（不依賴 global.js）
        if (typeof busuanzi !== 'undefined') {
            loadScript("//busuanzi.ibruce.info/busuanzi/2.3/busuanzi.pure.mini.js", true, false);
        }
        // FontAwesome 已改由 CSS preload 處理，不再使用 JS 版本
    });
</script>

{{--@if(config('app.env' ) == 'production')--}}
{{--    <script src="{{url('/js/blog/face-book-chat.js?v='.config('app.version'))}}"></script>--}}
{{--@endif--}}
</body>
</html>
