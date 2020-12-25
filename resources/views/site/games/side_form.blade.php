<form class="row" id="player-select" novalidate="novalidate">
    <div class="form-group col-md-6">
        <label class="control-label">{{trans('app.show_according')}} :</label>
        <select class="form-control filter_playersTeams" id="filter_playersTeams">
            <optgroup label="{{trans('app.default')}}">
                <option value="all" aria-selected="true">{{trans('app.all_players')}}</option>
            </optgroup>
<!--    الافتراضي        <optgroup label="الموقع">
                @foreach($locations as $key_loc=>$val_loc)
                <option value="{{$val_loc->type_key}}/location">{{$val_loc->$value_lang}}</option>
                @endforeach
            </optgroup>-->
            <optgroup label="{{trans('app.player_location')}}">
                <option value="goalkeeper/location" >{{trans('app.goalkeeper')}}</option>
                <option value="attacker_center/location" >{{trans('app.attacker_center')}}</option>
                <option value="defender_center/location" >{{trans('app.defender_center')}}</option>
                <option value="center_line/location" >{{trans('app.center_line')}}</option>
            </optgroup>
            <optgroup label="{{trans('app.team')}}">
                @foreach($teams as $key_tem=>$val_tem)
                <option value="{{$val_tem->link}}/team" >{{$val_tem->name}}</option>
                @endforeach
            </optgroup>
        </select>
    </div>
    <input type="hidden" name="location" class="form-control val_loc_player hidden" id="val_loc_player">
    <div class="form-group col-md-6">
        <label class="control-label"> {{trans('app.sort_by')}}:</label>
        <select class="form-control order_playersTeams" id="order_playersTeams">
            <!--<option value="point">{{trans('app.point')}}</option>-->
            <option value="heigh_price">{{trans('app.heigh_price')}}</option>
            <option value="low_price">{{trans('app.low_price')}}</option>
            <option value="heighest_point">{{trans('app.the_highest_point')}}</option>
            <option value="lowest_point">{{trans('app.the_lowest_point')}}</option>
            <!--<option value="goon">{{trans('app.goon')}}</option>-->
        </select>
    </div>
    <!--<div class="form-group ">
        <label class="control-label">{{trans('app.search')}}:</label>
        <input type="text" name="search" class="form-control word_search_player" id="word_search_player">
    </div>-->
    <!--<div class="form-group ">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <label class="control-label">{{trans('app.from_price')}}:</label>
                <input type="text" name="from_price" class="form-control from_price_player" id="from_price_player">
            </div>
            <div class="col-md-6 col-sm-6">
                <label class="control-label"> {{trans('app.to_price')}}:</label>
                <input type="text" name="to_price" class="form-control to_price_player" id="to_price_player">
            </div>
        </div>
    </div>-->
<!--<div class="form-group">
    <input type="submit" name="submit_search" value="{{trans('app.search')}}" class="center-block butn butn-bg submit_search_player" id="submit_search_player">
</div>-->
</form>