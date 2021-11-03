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
    <link rel="icon" type="image/x-icon" href="{{asset('/assets/favicon.ico')}}"/>
    <!-- Font Awesome icons (free version)-->
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet"
          type="text/css"/>
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800"
        rel="stylesheet" type="text/css"/>
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{secure_url('/css/styles.css?v='.config('app.version'))}}" rel="stylesheet"/>
    <link href="{{secure_url('/css/main.css?v='.config('app.version'))}}" rel="stylesheet"/>
    <link href="{{secure_url('/css/badge.css?v='.config('app.version'))}}" rel="stylesheet"/>
    <link href="https://cdn.ckeditor.com/4.8.0/standard/skins/moono-lisa/editor.css" rel="stylesheet"/>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NNCGJ1VG5L"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-NNCGJ1VG5L');
    </script>
</head>
<body>
<!-- Messenger 洽談外掛程式 Code -->
<div id="fb-root"></div>
<!-- Your 洽談外掛程式 code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>
<div class="loader" id="loading" style="display:none;">
    <img src="{{asset('/assets/img/loader.gif')}}" alt="Loading..."/>
</div>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="/">{{ config('app.name', 'Laravel') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="/">Home</a></li>
                @if(is_null(\Illuminate\Support\Facades\Auth::user()))
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="/login">LOGIN</a></li>
                @else
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="{{route('article.create')}}">POST</a>
                    </li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" id="logout" href="/logout">LOGOUT</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
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
<!-- jquery-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@yield("content")
<!-- Footer-->
<footer class="border-top">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <ul class="list-inline text-center">
                    <li class="list-inline-item">
                        <a href="https://www.instagram.com/comman24/" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-instagram fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://www.facebook.com/roy22887" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://github.com/cc711612" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                </ul>
                <div class="small text-center text-muted fst-italic">Copyright &copy; 2021 Roy<p id="busuanzi_value_site_pv"></p></div>
            </div>
        </div>
    </div>
</footer>
<script>
    let logout_uri = '{{route('logout')}}';
</script>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script async src="//busuanzi.ibruce.info/busuanzi/2.3/busuanzi.pure.mini.js"></script>
<script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
<!-- Core theme JS-->
<script src="{{asset('/js/blog/global.js')}}"></script>
<script src="{{asset('/js/blog/logout.js')}}"></script>
<script src="{{asset('/js/blog/scripts.js')}}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{asset('/js/blog/bobee.js')}}"></script>
<script>
    @if(config('app.env' )!= 'local')
    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "103818151196273");
    chatbox.setAttribute("attribution", "biz_inbox");
    window.fbAsyncInit = function () {
        FB.init({
            xfbml: true,
            version: 'v12.0'
        });
    };
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/zh_TW/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    @endif
    // 線上人數
    let onlineUsers = 0;

    function update_online_counter() {
        document.getElementById('online').textContent = '' + onlineUsers;
    }

    window.Echo.join('common_room')
        .here((users) => {
            onlineUsers = users.length;
            update_online_counter();
        })
        .joining((user) => {
            onlineUsers++;
            update_online_counter();
        })
        .leaving((user) => {
            onlineUsers--;
            update_online_counter();
        });
</script>
</body>
</html>
