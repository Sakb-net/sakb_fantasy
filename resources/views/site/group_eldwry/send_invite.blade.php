<form method="post">
    <div class="notif-msg"></div>
    <div class="col-md-12">
        <div class="form-group">
            <label> {{trans('app.enter_mobile_number_or_email_of_friend')}}:</label>
            <input type="text" class="form-control val_invite_emailphone" value="" placeholder="{{trans('app.enter_mobile_number_or_email_of_friend')}}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <input class="butn butn-bg m0 send_invite_emailphone " value=" {{trans('app.send_invitation')}}">
        </div>
    </div>
</form>