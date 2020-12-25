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
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="box">
            <div class="box-body ">
                <div class="form-group ">
                    <label>{{trans('app.add')}} {{trans('app.all')}} </label>
                    <a id="addOpta" data-id='store' data-name="{{trans('app.all_add_opta')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.all')}} " class="btn btn-success fa fa-plus"></a>
                    {!! Form::open(['method' => 'post','route' =>'admin.opta.store','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'store']) !!}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
</div>