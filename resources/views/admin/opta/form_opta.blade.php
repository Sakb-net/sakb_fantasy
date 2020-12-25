<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="box">
            <div class="box-body ">
                <div class="form-group" style="text-align: center;font-weight: bold; font-size:20px;">
                    <label>
                        OPTA / FANTASY DATA
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
                    <label>{{trans('app.add')}} {{trans('app.championship')}} </label>
                    <a id="addOpta" data-id='championship' data-name="{{trans('app.championship')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.championship')}} " class="btn btn-success fa fa-plus"></a>
                    {!! Form::open(['method' => 'post','route' =>'admin.opta.championship','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'championship']) !!}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
      <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                <div class="form-group ">
                    <label>{{trans('app.add')}} {{trans('app.eldwry')}} </label>
                    <a id="addOpta" data-id='eldwry' data-name="{{trans('app.eldwry')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.eldwry')}} " class="btn btn-success fa fa-plus"></a>
                    {!! Form::open(['method' => 'post','route' =>'admin.opta.eldwry','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'eldwry']) !!}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                <div class="form-group ">
                    <label>{{trans('app.add')}} {{trans('app.teams')}} </label>
                    <a id="addOpta" data-id='teams' data-name="{{trans('app.teams')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.teams')}} " class="btn btn-success fa fa-plus"></a>
                    {!! Form::open(['method' => 'post','route' =>'admin.opta.teams','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'teams']) !!}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                <div class="form-group ">
                    <label>{{trans('app.add')}} {{trans('app.players')}} </label>
                    <a id="addOpta" data-id='players' data-name="{{trans('app.players')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.players')}} " class="btn btn-success fa fa-plus"></a>
                    {!! Form::open(['method' => 'post','route' =>'admin.opta.players','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'players']) !!}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                <div class="form-group ">
                    <label>{{trans('app.add')}} {{trans('app.subeldwrys')}} </label>
                    <a id="addOpta" data-id='subeldwrys' data-name="{{trans('app.subeldwrys')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.subeldwrys')}} " class="btn btn-success fa fa-plus"></a>
                    {!! Form::open(['method' => 'post','route' =>'admin.opta.subeldwrys','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'subeldwrys']) !!}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
      <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                <div class="form-group ">
                    <label>{{trans('app.add')}} {{trans('app.matches')}} </label>
                    <a id="addOpta" data-id='matches' data-name="{{trans('app.matches')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.matches')}} " class="btn btn-success fa fa-plus"></a>
                    {!! Form::open(['method' => 'post','route' =>'admin.opta.matches','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'matches']) !!}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->

</div>