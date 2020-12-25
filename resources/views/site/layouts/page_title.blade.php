<div class="myinner-banner best-teamimg">
    <div class="opacity opcit-teamimg" @if(isset($team_image_fav) && !empty($team_image_fav) ) style="background-image: url({{$team_image_fav}}) !important;" @endif>
        <ul>
            <li><a href="{{ route('home') }}">{{trans('app.Home')}}</a></li>
            <li>/</li>
            <li>{{$page_title}}</li>
        </ul>
        <!--<h2>{{$page_title}}</h2>-->
    </div>
</div>
