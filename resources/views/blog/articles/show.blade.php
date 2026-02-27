@extends("blog.main")
@push('css-plugins')
<link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/styles/github-dark.min.css" rel="stylesheet">
@endpush
@push('scripts')
    <!-- 引入 Highlight.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/highlight.min.js"></script>
    <!-- 啟用 Highlight.js -->
    <script>hljs.highlightAll();</script>
@endpush
@section("content")
    <!-- Page Header-->
    <header class="masthead">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="post-heading">
                        <h1>{{$Html->element->title}}</h1>
                        <h2 class="subheading">{{$Html->element->sub_title}}</h2>
                        <span class="meta">
                            Posted by
                            <a href="{{$Html->element->actions->user_uri}}">{{$Html->element->user_name}}</a>
                            on {{$Html->element->updated_at}}
                        </span>
                        <div class="mt-2 d-flex align-items-center justify-content-center text-center flex-wrap gap-3 article-meta-tools">
                            <!-- 瀏覽人數統計 -->
                            <livewire:article-viewer-count :articleId="$Html->element->id" />
                            <!-- 點讚功能 -->
                            <livewire:article-like :articleId="$Html->element->id" />
                            <span class="d-inline-flex align-items-center text-light article-meta-views">
                                <span id="busuanzi_value_page_pv"></span>
                                <span class="ms-1">Views</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Post Content-->
    <article class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="share" style="text-align: right!important;">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{\Illuminate\Support\Facades\URL::current()}}" target="_blank" title="facebook分享"><i class="fab fa-2x fa-facebook-square"></i></a>
                <a href="https://social-plugins.line.me/lineit/share?url={{\Illuminate\Support\Facades\URL::current()}}&amp;from=line_scheme&amp;" title="Line分享" target="_blank"><i class="fab fa-2x fa-line text-success mr-1"></i></a>
            </div>
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-12 article-content">
                    {!! $Html->element->content !!}
                </div>
            </div>
        </div>
    </article>
    
    <!-- 增強的留言系統 -->
    <livewire:comment :element="$Html->element">
@endsection
