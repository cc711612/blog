<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a href="{{config('app.url')}}" title="index">
                <img src="https://roy.usongrat.tw/storage/images/2021/08/19/gotogoogle.png" alt="Logo"
                     style="height: 20rem;">
            </a>
        </x-slot>

        <x-jet-validation-errors class="mb-4"/>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{route('login.api')}}" id="form">
            @csrf
            <div>
                <x-jet-label for="email" value="{{ __('Email') }}"/>
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                             required autofocus/>
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}"/>
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required
                             autocomplete="current-password"/>
            </div>

            <div class="block mt-4">
                {{--                <label for="remember_me" class="flex items-center">--}}
                {{--                    <x-jet-checkbox id="remember_me" name="remember"/>--}}
                {{--                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>--}}
                {{--                </label>--}}
                @if (Route::has('password.request'))
                    <div style="text-align: left">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif
            </div>
            <div class="flex items-center justify-end mt-4">
                <div class="facebook_login ml-4">
                    <a href="{{route('social.facebook.login')}}" title="facebook登入"><i
                            class="fab fa-2x fa-facebook-square"></i></a>
                </div>
                <div class="line_login ml-4">
                    <a href="{{route('social.line.login')}}" title="Line登入"><i
                            class="fab fa-2x fa-line text-success mr-1"></i></a>
                </div>
                <button type="button" id="login"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-4">
                    Log in
                </button>
            </div>
        </form>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="{{asset('/js/blog/global.js')}}"></script>
        <script src="{{asset('/js/blog/login.js')}}"></script>
    </x-jet-authentication-card>
</x-guest-layout>
