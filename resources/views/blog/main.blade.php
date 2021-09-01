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
    <link href="{{asset('/css/styles.css?v='.config('app.version'))}}" rel="stylesheet"/>
    <link href="{{asset('/css/main.css?v='.config('app.version'))}}" rel="stylesheet"/>
    <link href="https://cdn.ckeditor.com/4.8.0/standard/skins/moono-lisa/editor.css" rel="stylesheet"/>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NNCGJ1VG5L"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-NNCGJ1VG5L');
    </script>
</head>
<body>
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
                <div class="small text-center text-muted fst-italic">Copyright &copy; Roy 2021</div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
<!-- Core theme JS-->
<script src="{{asset('/js/blog/global.js')}}"></script>
<script src="{{asset('/js/blog/logout.js')}}"></script>
<script src="{{asset('/js/blog/scripts.js')}}"></script>
</body>
</html>
