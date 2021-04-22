<div class="wrapper">

    @include('layouts.navbars.auth')

    <div class="main-panel">
            {{-- Hide top nav to simplify --}}
{{--        @include('layouts.navbars.navs.auth')--}}
        @yield('content')
        @include('layouts.footer')
    </div>
</div>
