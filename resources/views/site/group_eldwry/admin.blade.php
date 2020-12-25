<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="section-head">
                    <h4>{{trans('app.administer')}}</h4>
                </div>
                <div class="mytab-content">
                    <!-- =========== Dear Eman, Take care with href & Id here :) ============ -->
                    <div class="panel-group" id="accordion">
                        <!-- change code -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#Q1" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                     {{trans('app.league_code')}}
                                    </a>
                                </h4>
                            </div>
                            <div id="Q1" class="panel-collapse collapse in">
                                <div class="panel-body text-center">
                                    @include('site.group_eldwry.copy_code')
                                </div>
                            </div>
                        </div>
                        <!-- invite friend -->
                        <div class="panel panel-default hidden">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a  href="#Q2" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                    {{trans('app.invite_friend')}}
                                    </a>
                                </h4>
                            </div>
                            <div id="Q2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    @include('site.group_eldwry.send_invite')
                                </div>
                            </div>
                        </div>
                        <!-- edit deatails -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#Q3" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                    {{trans('app.league_detailes')}}
                                    </a>
                                </h4>
                            </div>
                            <div id="Q3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form  method="post">
                                        @include('site.group_eldwry.create_form')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input class="butn butn-bg m0 update_groupEldwry" value="{{trans('app.saving_changes')}}">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- delete player -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#Q4" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                    {{trans('app.delete_league_player')}}
                                    </a>
                                </h4>
                            </div>
                            <div id="Q4" class="panel-collapse collapse">
                                <div class="panel-body">
                                   @include('site.group_eldwry.form_delete_user')
                                </div>
                            </div>
                        </div>
                        <!-- change admin -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#Q5" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                   {{trans('app.change_league_admin')}}
                                    </a>
                                </h4>
                            </div>
                            <div id="Q5" class="panel-collapse collapse">
                                <div class="panel-body">
                                   @include('site.group_eldwry.form_add_admin')
                                </div>
                            </div>
                        </div>
                        <!-- delete league -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#Q6" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                    {{trans('app.delete_league')}}
                                    </a>
                                </h4>
                            </div>
                            <div id="Q6" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="notif-msg"></div>
                                     <div class="form-group">
                                        <p>{{trans('app.msg_delete_league')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <a class="butn butn-bg m0 stop_group_eldwry">{{trans('app.delete_league')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end panel group -->
                </div>
            </div>
        </div>
    </div>
</section>