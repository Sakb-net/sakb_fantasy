<form  method="post">
    <div class="notif-msg"></div>
    <div class="col-md-12">
        <div class="form-group">
            <p>{{trans('app.select_player_tobe_manager')}}</p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label>{{trans('app.players_list')}}:</label>
            <input class="datalist-input admin_user_group" type="text" list="players" />
            <datalist id="players" class="draw_users_group">
                <!-- <option>Fayez</option> -->
            </datalist>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <input  class="butn butn-bg m0 addadmin_group_eldwry" value=" {{trans('app.change_league_admin')}}">
        </div>
    </div>
</form>