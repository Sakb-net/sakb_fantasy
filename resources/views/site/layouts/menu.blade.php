<li @if(isset($activ_menu)&&$activ_menu==1) class="active" @endif><a href="{{ route('home') }}">{{trans('app.Home')}}</a></li>
<li class="dropdown-holder @if(isset($activ_menu)&&in_array($activ_menu,[4,41,42,43])) active @endif">
    <a href="#">{{trans('app.news')}}</a>
    <ul class="sub-menu">
        <li><a href="{{ route('news.index') }}" @if(isset($activ_menu)&&$activ_menu==41) class="active_subli" @endif >{{trans('app.news')}}</a></li>
        <li><a href="{{ route('videos.index') }}" @if(isset($activ_menu)&&$activ_menu==43) class="active_subli" @endif >{{trans('app.videos')}}</a></li>
    </ul>
    <i class="fa fa-caret-down icon" aria-hidden="true"></i>
</li>
<li @if(isset($activ_menu)&&$activ_menu==2) class="active" @endif><a href="{{ route('fixtures.index') }}">{{trans('app.fixtures')}}</a></li>
<li @if(isset($activ_menu)&&$activ_menu==3) class="active" @endif><a href="{{ route('statics.index') }}">{{trans('app.statics')}}</a></li>
<li @if(isset($activ_menu)&&$activ_menu==5) class="active" @endif><a href="{{ route('awards.index') }}">{{trans('app.awards')}}</a></li>
<li @if(isset($activ_menu)&&$activ_menu==6) class="active" @endif><a href="{{ route('instractions.index') }}">{{trans('app.instractions')}}</a></li>
<li @if(isset($activ_menu)&&$activ_menu==12) class="active" @endif><a href="{{ route('contact') }}">{{trans('app.contact_us')}}</a></li>
@guest
    <li class="join-us">
    <a href="{{ url('login') }}">{{trans('app.Login')}}</a>
    </li>
    @else
    <li class="dropdown-holder user-prof @if(isset($activ_menu)&&$activ_menu==7) active @endif">
        <a href="#">{{ Auth::user()->display_name }}</a>
        <ul class="sub-menu">
            <li><a href="{{ route('profile.index') }}"><i class="fa fa-pencil"></i>{{trans('app.update_profile')}}</a></li>
            <li><a href="{{ route('game.index') }}"><i class="fa fa-futbol-o"></i>{{trans('app.game_page')}}</a></li>
             @if( $admin_panel > 0)
                <li><a href="{{ route('admin.index') }}"><i class="fa fa-fw fa-dashboard"></i>{{trans('app.control_panel')}}</a></li>
            @endif
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i>{{trans('app.Logout')}}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
        <i class="fa fa-caret-down icon"></i>
    </li>
@endguest
@include('site.layouts.lang_menu')
