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
                <div class="col-sm-12 col-md-7 col-lg-7">
                 {!! Form::open(['method' => 'post','route' =>'admin.opta.result_subeldwry','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'result_subeldwry']) !!}
                             <div class="form-group">
                            <label>{{trans('app.choose')}} 
                            {{trans('app.subeldwry')}} </label>
                            <select class="select2" name="subeldwry_id">
                                <option value="0">{{trans('app.choose').' '.trans('app.subeldwry')}}</option>
                                @foreach($subeldwrys as $key_sub=>$val_sub)
                                <option value="{{$val_sub->id}}">
                                    {{trans('app.week')}} &nbsp;
                                    (  {{$val_sub->num_week}} )
                                    &nbsp;&nbsp;&nbsp;{{$val_sub->name}}  
                                </option>
                                @endforeach
                            </select>
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-sm-12 col-md-5 col-lg-5">
                    <div class="form-group" style="margin-top:20px;">
                        <label>{{trans('app.add')}} {{trans('app.result_subeldwry')}} </label>
                        <a id="addOpta" data-id='result_subeldwry' data-name="{{trans('app.result_subeldwry')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.result_subeldwry')}} " class="btn btn-success fa fa-plus"></a>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
</div>