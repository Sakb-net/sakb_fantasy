<section class="section-padding wow fadeInUp">
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="section-head">
                <h4>{{trans('app.leagues')}}</h4>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                   <div class="row text-center">
                        <div class="col-md-6 col-sm-6 p0">
                            <a class="butn butn-bg w100 group_eldwry_join">{{trans('app.join_a_league')}} 
                                <i class="fa fa-chevron-left pull-right"></i>
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-6 p0">
                            <a class="butn butn-bg w100 group_eldwry_create">{{trans('app.create_a_league')}}
                                <i class="fa fa-chevron-left pull-right"></i>
                            </a>
                        </div>
                    </div> 
                </div>
            </div>
            @include('site.group_eldwry.eldwry')
 		</div>
    	</div>
	</div>
</section>
@include('site.group_eldwry.modal.create_btn')
