<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="section-head">
                    <h4>{{trans('app.join_a_league')}}</h4>
                </div>
                <div class="DeadlineBar">
                    <h3><strong>{{trans('app.can_join_num_private_leagues')}}</strong></h3>
                </div>
                <div class="after-DeadlineBar"></div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>{{trans('app.private_league')}}</h3>
                    </div>
                    <div class="panel-body">
                        <p>{{trans('app.join_num_private_league_enter_code')}}</p>
                        <form class="form-inline text-center" action="#">
                            <div class="notif-msg"></div>
                            <div class="form-group">
                                <label>{{trans('app.league_code')}}:</label>
                                <input type="text" class="form-control input_join_group_eldwry" placeholder="{{trans('app.league_code')}}">
                            </div>
                            <button class="butn butn-bg add_join_group_eldwry" data-type="classic"> {{trans('app.send_invitation')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>