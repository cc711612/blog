<nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="{{route('website.index')}}">{{ config('app.name', 'Laravel') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="{{route('website.index')}}">Home</a>
                </li>
                @if(is_null(\Illuminate\Support\Facades\Auth::user()))
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="{{route('login')}}">LOGIN</a>
                    </li>
                @else
                    @if(in_array(\Illuminate\Support\Facades\Auth::id(),config('admin.user_ids')))
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="{{route('admin.home')}}">Admin</a>
                        </li>
                    @endif
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="{{route('article.create')}}">POST</a>
                    </li>

                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" id="logout" href="/logout">LOGOUT</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
