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
            <div class="flex items-center justify-end mt-4">
                <div class="facebook_login ml-4">
                    <a href="{{route('social.facebook.login')}}" title="facebook登入">
                        <img src="{{asset('assets/img/auth/facebook.png')}}" alt="facebook登入" style="width: 2rem;">
                    </a>
                </div>
                <div class="line_login ml-4">
                    <a href="{{route('social.line.login')}}" title="Line登入">
                        <img src="{{asset('assets/img/auth/line.png')}}" alt="Line登入" style="width: 2rem;">
                    </a>
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
