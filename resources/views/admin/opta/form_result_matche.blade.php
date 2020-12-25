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
                    {!! Form::open(['method' => 'post','route' =>'admin.opta.result_matche','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'result_matche']) !!}
                            <div class="form-group">
                                <label>{{trans('app.choose')}} 
                                {{trans('app.match')}} </label>
                                <select class="select2" name="matche_id">
                                    <option value="0">{{trans('app.choose').' '.trans('app.match')}}</option>
                                    @foreach($matches as $key_match=>$val_match)
                                    <option value="{{$val_match->id}}">
                                      {{$val_match->teams_first->name}}    &nbsp;&nbsp;   Vs &nbsp;&nbsp;{{$val_match->teams_second->name}} 
                                          &nbsp;&nbsp;&nbsp;
                                           (  {{$val_match->name}} )
                                    </option>
                                    @endforeach
                                </select>
                            </div>  
                        {!! Form::close() !!}
                    
                </div>
                <div class="col-sm-12 col-md-5 col-lg-5">
                    <div class="form-group" style="margin-top:20px;">
                        <label>{{trans('app.add')}} {{trans('app.result_matche')}} </label>
                        <a id="addOpta" data-id='result_matche' data-name="{{trans('app.result_matche')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.result_matche')}} " class="btn btn-success fa fa-plus"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
</div>