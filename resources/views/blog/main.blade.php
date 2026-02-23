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
    
    <!-- Critical CSS inline -->
    <style>
        .masthead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 8rem 0 4rem;
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
        .container {
            max-width: 900px;
        }
        .form-control {
            border-radius: 8px;
            border: 2px solid #dee2e6;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #e67e22;
            box-shadow: 0 0 0 0.2rem rgba(230, 126, 34, 0.25);
        }
    </style>
    
    <!-- Preload critical CSS -->
    <link rel="preload" href="{{url('/css/styles.css?v='.config('app.version'))}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{url('/css/styles.css?v='.config('app.version'))}}"></noscript>
    
    <link rel="preload" href="{{url('/css/custom-theme.css?v='.config('app.version'))}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{url('/css/custom-theme.css?v='.config('app.version'))}}"></noscript>
    
    <!-- Non-critical CSS loaded asynchronously -->
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
        
        // 預載入關鍵圖片
        const criticalImages = [
            '{{config('filesystems.disks.s3.url')."assets/img/home-bg.webp"}}'
        ];
        
        criticalImages.forEach(function(src) {
            const img = new Image();
            img.src = src;
        });
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
    if ('serviceWorker' in navigator && config('app.env') === 'production') {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js')
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
    <img src="{{config('filesystems.disks.s3.url') . 'assets/img/loader.gif'}}" alt="Loading..."/>
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
@yield("content")
<!-- Footer-->
@include("layouts.footer")
<script>
    let logout_uri = '{{route('logout')}}';
    // 線上人數
    let onlineUsers = 0;
</script>
<!-- jquery-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
@livewireScripts
@stack('scripts')
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" defer></script>
<!-- Core theme JS - 延後載入 -->
<script>
    window.addEventListener('load', function() {
        // 延後載入非關鍵 JS
        loadScript("{{url('/js/blog/global.js')}}");
        loadScript("{{url('/js/blog/logout.js?v='.config('app.version'))}}");
        loadScript("{{url('/js/blog/scripts.js?v='.config('app.version'))}}");
        loadScript("{{url('js/app.js?v='.config('app.version')) }}");
        loadScript("{{url('/js/blog/online.js?v='.config('app.version'))}}");
        
        // 性能監控
        @if(config('app.env') === 'production')
        loadScript("{{url('/js/performance-monitor.js')}}");
        @endif
        
        // 第三方腳本最後載入
        if (typeof busuanzi !== 'undefined') {
            loadScript("//busuanzi.ibruce.info/busuanzi/2.3/busuanzi.pure.mini.js", true, false);
        }
        loadScript("https://use.fontawesome.com/releases/v5.15.3/js/all.js", true, false);
    });
</script>

{{--@if(config('app.env' ) == 'production')--}}
{{--    <script src="{{url('/js/blog/face-book-chat.js?v='.config('app.version'))}}"></script>--}}
{{--@endif--}}
</body>
</html>
