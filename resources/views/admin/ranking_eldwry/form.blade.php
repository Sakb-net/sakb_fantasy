<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="box">
            <div class="box-body ">
                <div class="form-group" style="text-align: center;font-weight: bold; font-size:20px;">
                    <label>
                        {{trans('app.add')}} {{trans('app.ranking_eldwry')}}
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                <div class="form-group ">
                    <label>{{trans('app.add')}} {{trans('app.ranking_eldwry')}} </label>
                    <a id="addOpta" data-id='ranking_eldwry' data-name="{{trans('app.ranking_eldwry')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.ranking_eldwry')}} " class="btn btn-success fa fa-plus"></a>
                    {!! Form::open(['method' => 'post','route' =>'admin.ranking_eldwry.store','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'ranking_eldwry']) !!}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->

</div>