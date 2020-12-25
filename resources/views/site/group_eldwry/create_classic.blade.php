<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="section-head">
                    <h4>{{trans('app.create_classic_league')}}</h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>{{trans('app.create_classic_league')}}</h3>
                    </div>
                    <div class="panel-body">
                        <form  method="post">
                            @include('site.group_eldwry.create_form')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input class="butn butn-bg m0 store_groupEldwry" value="{{trans('app.create')}}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>        