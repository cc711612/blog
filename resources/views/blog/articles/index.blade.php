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
        <div class="content-wrapper">
            <!-- 主要內容區 -->
            <div class="main-content">
                <!-- Livewire 文章搜尋組件 -->
                <livewire:article-search />
            </div>
            
            <!-- 側邊欄 -->
            <div class="sidebar d-none d-lg-block">
                <!-- 最新文章 -->
                <div class="sidebar-widget">
                    <h3>最新文章</h3>
                    <ul class="recent-posts">
                        @forelse($recentArticles as $article)
                            <li>
                                <a href="{{ route('article.show', ['article' => $article->id]) }}" class="recent-post-title">
                                    {{ Str::limit($article->title, 50) }}
                                </a>
                                <span class="recent-post-date">{{ $article->created_at->format('Y-m-d') }}</span>
                            </li>
                        @empty
                            <li>
                                <span class="text-muted">暫無文章</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
                
                <!-- 熱門關鍵字 -->
                <div class="sidebar-widget">
                    <h3>熱門關鍵字</h3>
                    <div class="tag-cloud">
                        @foreach($popularKeywords as $keyword)
                            <a href="{{ url('/?search=' . urlencode($keyword)) }}" class="tag">{{ $keyword }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
