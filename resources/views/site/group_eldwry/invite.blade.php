<section class="section-padding wow fadeInUp invite-page">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <h3 class="d-inline">{{trans('app.league_join_code')}}: </h3><p class="code d-inline valcode_group"> </p> 
                    <a onclick="copyToClipboard('.code')" class="butn butn-bg btn-copy" style="display: inline"><i class="fa fa-clone"></i> <span class="hide-option">{{trans('app.copy_code')}}</span></a> 
                </div>
                <div class="form-group">
                    <p>---------- <strong> {{trans('app.or')}} </strong> ----------</p>
                    <p class="invitelink hide invitelink_group"></p>
                    <a onclick="copyToClipboard('.invitelink')" class="butn butn-bg btn-copy d-inline"><i class="fa fa-clone"></i> {{trans('app.copy_invitation_link')}}</a>
                </div>
                <hr>
                <div class="form-group">
                    <p> {{trans('app.msg_copy_email_and_send_invitation')}}</p>
                    <!-- <a onclick="javascript:window.location='mailto:?subject=فانتازي الدوري السعودي&body=مرحبا,%0D%0A يشرفني انضمامك إلي الدوري الخاص بي في فانتازي الدوري السعودي %0D%0A https://fantgame.sakb-co.com.sa %0D%0A كود الدوري: wmzz4d %0D%0A في انتظارك'" class="butn butn-bg btn-copy d-inline">
                    <i class="fa fa-envelope-o"></i> دعوة عبر البريد الإلكتروني
                    </a> -->
                                   
                    <a class="butn butn-bg btn-copy d-inline inviteEmailtesxt_group">
                        <i class="fa fa-envelope-o"></i>{{trans('app.email_invitation')}}
                    </a>
                </div>
                <hr>
                <div class="form-group">
                    <p>{{trans('app.msg_using_webmail')}}:</p>
                    <div class="invite-text">
                        {{trans('app.hello')}}
                        <br>{{trans('app.honored_join_myleague')}}
                        <br>{{trans('app.link_below_add_automatically')}} 
                        <br><span class="invitelink_group"></span> 
                        <br>{{trans('app.league_code')}}: <span class="valcode_group"></span>
                        <br>{{trans('app.waiting_for_you')}}
                    </div>
                    <a onclick="copyToClipboard('.invite-text')" class="butn butn-bg btn-copy d-inline"><i class="fa fa-clone"></i> {{trans('app.copy_text_of_email')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>