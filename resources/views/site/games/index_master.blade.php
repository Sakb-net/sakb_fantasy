<div class="Draw_tab_game_transfer">
    <section class="section-padding wow fadeInUp">
        <div class="container">
            <div class="row">
                <div class="col-md-9 p0-sm" id= stretch>
                    @include('site.games.cost_point')
                    <div class="all-notif-msg"></div>
                    <!--site.games.alert_msg'-->
                    @include('site.games.game')
                    @include('site.games.fixtures')
                </div>
                <!--=======side bar ==============-->
                <div class="col-md-3 transform" id="filter">
                    <div class="div_filter_side">
                        <div class="filter">
                            <h1>{{trans('app.choose_players')}}<span class="back">{{trans('app.back')}}<i class="fa fa-chevron-left"></i> </span></h1>
                            @include('site.games.transformation_items')
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('site.games.all_modal')