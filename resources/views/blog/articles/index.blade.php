@extends("blog.main")
@section("content")
    <!-- Page Header-->
    <header class="masthead" style="background-image: url('{{config('filesystems.disks.s3.url')."assets/img/home-bg.webp"}}')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="site-heading">
                        <h1>{{ config('app.sub_title', 'Laravel') }}</h1>
                        <span class="subheading">Everything be all right</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content-->
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <!-- Livewire 文章搜尋組件 -->
                <livewire:article-search />
            </div>
        </div>
    </div>

@endsection
