<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="box">
            <div class="box-body ">
                <div class="form-group" style="text-align: center;font-weight: bold; font-size:20px;">
                    <label>
                        {{trans('app.update')}} {{trans('app.ranking_eldwry')}}
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
                    {!! Form::open(['method' => 'post','route' =>'admin.ranking_eldwry.store_match','style'=>'display:inline']) !!}
                        {!! Form::submit('Add', ['class' => 'hide btn btn-success','data-addOpta-id' => 'ranking_eldwry_match']) !!}
                            <div class="form-group">
                                <label>{{trans('app.choose')}} 
                                {{trans('app.match')}} </label>
                                <select class="select2" name="match_id">
                                    <option value="0">{{trans('app.choose').' '.trans('app.match')}}</option>
                                    @foreach($matches as $key_match=>$val_rinking)
                                        @php $val_match=$val_rinking->match @endphp
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
                        <label>{{trans('app.update')}} {{trans('app.ranking_eldwry')}} </label>
                        <a id="addOpta" data-id='ranking_eldwry_match' data-name="{{trans('app.ranking_eldwry')}}" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.update')}}  {{trans('app.ranking_eldwry')}} " class="btn btn-primary fa fa-plus"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************** -->
</div>