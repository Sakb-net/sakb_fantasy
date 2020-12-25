<div class="row">
    <div class="col-sm-8 col-md-8 col-lg-8">
        <div class="box">
            <div class="box-body">
                {!! Form::hidden('setting_key', $setting_key) !!}
                <div class="form-group">
                    <label>{{trans('app.lineup')}}  </label>
                    <div class="row">
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <label> {{trans('app.defender')}} </label>
                            {!! Form::number('name_one', $name_one, array('class' => 'form-control','required'=>'','max'=>'5','min'=>'2')) !!}
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <label> {{trans('app.line')}} </label>
                            {!! Form::number('name_second', $name_second, array('class' => 'form-control','required'=>'','max'=>'5','min'=>'2')) !!}
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <label> {{trans('app.Forward')}} </label>
                            {!! Form::number('name_three', $name_three, array('class' => 'form-control','required'=>'','max'=>'3','min'=>'1')) !!}
                        </div>
                    </div>    
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.slug')}} </label>
                    @if($new > 0 )
                    {!! Form::text('setting_etc', null, array('class' => 'form-control')) !!}
                    @else
                    {!! Form::text('setting_etc', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
                @if($setting_active > 0&&$new ==0)
                <div class="form-group">
                    <label>{{trans('app.state')}} </label>
                    {!! Form::select('is_active',statusType() ,null, array('class' => 'select2')) !!}
                </div>
                @endif

                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info padding-40" >{{trans('app.save')}}</button>
                    <a href="{{$link_return}}" class="btn btn-primary padding-30">{{trans('app.back')}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 hidden">
        <div class="box">
            <div class="box-body ">
                
            </div>
        </div>
    </div>
</div>

