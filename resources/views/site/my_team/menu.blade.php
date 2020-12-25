<div class="myinner-banner" >
    <div class="container opacity p-b-0" 
    @if(isset($team_image_fav) && !empty($team_image_fav) ) style="background-image: url({{$team_image_fav}}) !important;" @endif >
        <h2>{{trans('app.saudi_league_fantasy')}}</h2>
        <div class="game-menu">
        	@if($found_point==1)
        		<a class="butn tab_menu_game tab_game_my_point @if($type_page=='my_point') active @endif">{{trans('app.my_point')}}</a>
        	@endif
            <a class="butn tab_menu_game tab_game_my_team @if($type_page=='my_team') active @endif">{{trans('app.my_team')}}</a>
            <a class="butn tab_menu_game tab_game_game_transfer @if($type_page=='game_transfer') active @endif" >{{trans('app.make_transfers')}}</a>
            <a class="butn tab_menu_game tab_game_group_eldwry @if($type_page=='group_eldwry') active @endif" >{{trans('app.leagues')}}</a>
        </div>
    </div>
</div>