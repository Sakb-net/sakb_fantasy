<div id="enterTeamModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('app.enter_team')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="post">
                            <div class="form-group">
                                <div class="notif-msg"></div>
                                <label>{{trans('app.specify_name_your_team')}}</label>
                                <input type="text" name="team-name" value="{{$team_name}}" class="form-control game_team_name" disabled>
                                <p class="alert alert-danger raduis team_name_error hide" style="padding: 0.5rem; margin-top: 5px;"></p>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input  type="submit" name="login-submit" id="login-submit" class="butn butn-bg submit_game_team" value="{{trans('app.enter_team')}}" disabled="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>