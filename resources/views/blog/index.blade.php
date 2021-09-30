@extends("blog.main")
@section("content")
<!-- Page Header-->
<header class="masthead" style="background-image: url('../assets/img/home-bg.jpg')">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="site-heading">
                    <h1>{{ config('app.name', 'Laravel') }}</h1>
                    <span class="subheading">Everything be all right</span>
                </div>
            </div>
        </div>
    </div>
</header>
    <!-- Main Content-->
<!-- Main Content-->
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7">
            <!-- Post preview-->
            @foreach($Html->elements as $element)
            <div class="post-preview">
                <a href="{{$element->actions->show_uri}}">
                    <h2 class="post-title">{{$element->title}}</h2>
                    <h3 class="post-subtitle">{{$element->sub_title}}</h3>
                </a>
                <p class="post-meta">
                    Posted by
                    <a href="{{$element->actions->user_uri}}">{{$element->user_name}}</a>
                    At {{$element->updated_at}}
                </p>
            </div>
            <!-- Divider-->
            <hr class="my-4" />
            @endforeach
            <!-- Pager-->
            <div class="d-flex justify-content-end mb-4">
                {!! $Html->page_link !!}
            </div>

        </div>
    </div>
</div>

@endsection
