@extends("blog.main")
@section("content")
    <!-- Page Header-->
    <header class="masthead" style="background-image: url('{{config('app.url')."assets/img/home-bg.webp"}}')">
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
                        <span id="busuanzi_value_page_pv"></span> Views
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
                {!! $Html->element->content !!}
            </div>
        </div>
    </article>
    <livewire:comment :element="$Html->element">
@endsection
