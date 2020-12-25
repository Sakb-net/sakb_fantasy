<!DOCTYPE html>
<html>
    <head>
        @include('site.layouts.head')
        @yield('after_head')
    </head>
    <body  class="" site-Homeurl="{{ route('home') }}" data-homelang="{{$cuRRlocal}}" data-user="{{ $user_key }}">
        <div class="main-page-wrapper">
            <div id="loader-wrapper">
                <div id="loader"></div>
            </div>
        @yield('content')
        @include('site.layouts.footer_bottom')        
        @include('site.layouts.foot')
        @yield('after_foot')
        </div>
    </body>
</html>
