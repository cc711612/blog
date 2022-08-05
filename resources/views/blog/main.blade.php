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
    <link rel="icon" type="image/x-icon" href="{{secure_url('/favicon.ico')}}"/>
<!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{secure_url('/css/styles.css?v='.config('app.version'))}}" rel="stylesheet"/>
    <link href="{{secure_url('/css/main.css?v='.config('app.version'))}}" rel="stylesheet"/>
    <link href="{{secure_url('/css/badge.css?v='.config('app.version'))}}" rel="stylesheet"/>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    @stack('css-plugins')
    <!-- END PAGE LEVEL PLUGINS -->
    @include("layouts.tracking_header")
    @laravelPWA
</head>
<body>
@include("layouts.tracking_noscript")
<!-- Messenger 洽談外掛程式 Code -->
<div id="fb-root"></div>
<!-- Your 洽談外掛程式 code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>
<div class="loader" id="loading" style="display:none;">
    <img src="{{asset('/assets/img/loader.gif')}}" alt="Loading..."/>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{secure_url('/js/blog/global.js')}}"></script>
@stack('scripts')
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script async src="//busuanzi.ibruce.info/busuanzi/2.3/busuanzi.pure.mini.js"></script>
<script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
<!-- Core theme JS-->
<script src="{{secure_url('/js/blog/logout.js?v='.config('app.version'))}}"></script>
<script src="{{secure_url('/js/blog/scripts.js?v='.config('app.version'))}}"></script>
<script src="{{secure_url('js/app.js?v='.config('app.version')) }}"></script>
<script src="{{secure_url('/js/blog/bobee.js?v='.config('app.version'))}}"></script>
<script src="{{secure_url('/js/blog/online.js?v='.config('app.version'))}}"></script>

@if(config('app.env' ) == 'production')
    <script src="{{secure_url('/js/blog/face-book-chat.js?v='.config('app.version'))}}"></script>
@endif
</body>
</html>
