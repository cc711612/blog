@extends("blog.main")
@section("content")
<!-- Page Header-->
<header class="masthead" style="background-image: url('../assets/img/home-bg.jpg')">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="post-heading">
                    <h1>{{$Html->element->title}}</h1>
                    <h2 class="subheading">{{$Html->element->sub_title}}</h2>
                    <span class="meta">
                            Posted by
                            <a href="#!">{{$Html->element->user_name}}</a>
                            on {{$Html->element->updated_at}}
                        </span>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Post Content-->
<article class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            {!! $Html->element->content !!}
        </div>
    </div>
</article>

@endsection
