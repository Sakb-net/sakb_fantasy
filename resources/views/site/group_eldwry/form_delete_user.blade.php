<form  method="post">
    <div class="notif-msg"></div>
    <div class="col-md-12">
        <div class="form-group">
            <p>{{trans('app.msg_choose_player_to_delete_from_list')}}</p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label>{{trans('app.players_list')}}:</label>
            <input class="datalist-input player_user_group" type="text" list="players" />
            <datalist id="players" class="draw_users_group">
                <!-- <option>Fayez</option> -->
            </datalist>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <input  class="butn butn-bg m0 deleteplayer_group_eldwry" value="{{trans('app.delete_player')}}">
        </div>
    </div>
</form>