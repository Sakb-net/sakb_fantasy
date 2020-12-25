<a class="butn click_leagueoptions hidden" data-toggle="modal" data-target="#league-options"></a>
<div id="league-options" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title name_groupEldwry"></h4>
            </div>
            <div class="modal-body">
                <div class="notif-msg"></div>
                <div class="row">
                    <div class="form-group">
                        <a class="butn butn-bg w100 show_group_groupeldwry" data-dismiss="modal">{{trans('app.standings')}}</a>
                        <a class="butn butn-bg w100 show_admin_groupeldwry" data-dismiss="modal">{{trans('app.administer')}}</a>
                         <a class="butn butn-bg w100 page_send_invite" data-dismiss="modal"> {{trans('app.invited_friends_to_league')}}</a>
                        <a class="butn butn-bg w100 leave_groupeldwry">{{trans('app.leave_league')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
