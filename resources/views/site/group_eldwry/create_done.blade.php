<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="invitation-container col-md-10 col-md-offset-1">
                <div class="form-group green">
                    <i class="fa fa-check-circle fa-5x" aria-hidden="true"></i>
                </div>
                <div class="section-head">
                    <h4>{{trans('app.league_created_successfully')}}</h4>
                </div>
                <div class="form-group">
                    <h3>{{trans('app.invite_your_friends')}}</h3>
                </div>
                <div class="invitation">
                    <div class="col-sm-6">
                        <label>{{trans('app.copying_following_code_and_sending')}}:</label>
                        @include('site.group_eldwry.copy_code')
                    </div>
                    <div class="or"> {{trans('app.or')}} </div>
                    <div class="col-sm-6">
                        <p class="invitelink hide invitelink_group"></p>
                        <a onclick="copyToClipboard('.invitelink')" class="butn butn-bg btn-copy d-inline"><i class="fa fa-clone"></i> {{trans('app.copy_invitation_link')}}</a>
                        <!-- @include('site.group_eldwry.send_invite') -->
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{route('game.group_eldwry.admin',$type_group)}}" class="butn m0 redirect_admin_group_eldwry"> {{trans('app.go_to_league_administration')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>    