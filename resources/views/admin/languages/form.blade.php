<div class="row">
    <div class="col-sm-9 col-md-9 col-lg-9">
        <div class="box">
            <div class="box-body">
                {!! Form::hidden('type', 'lang') !!}
                <div class="form-group">
                    <label>{{trans('app.name')}}  {{trans('app.lang')}} :</label>
                    {!! Form::text('name', null, array('class' => 'form-control','required'=>'')) !!}
                </div>
                <div class="form-group">
                    <label>{{trans('app.key')}}  {{trans('app.lang')}} :</label>
                    @if($new == 0)
                    {!! Form::text('lang', null, array('class' => 'form-control','disabled'=>'','required'=>'')) !!}
                    @else
                    {!! Form::text('lang', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
                <div class="form-group">
                    <label>{{trans('app.lang')}} Default:</label>
                    {!! Form::select('is_default',NostatusType() ,null, array('class' => 'select2')) !!}
                </div>
                @if($post_active > 0)
                    @if($new == 0)
                    <div class="form-group">
                        <label>{{trans('app.state')}} :</label>
                        {!! Form::select('is_active',statusType() ,null, array('class' => 'select2','required'=>'')) !!}
                    </div>
                    @endif
                @endif
                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info padding-40" >{{trans('app.save')}} </button>
                    <a href="{{$link_return}}" class="btn btn-primary padding-30">{{trans('app.back')}} </a>
                </div>
            </div>
        </div>
    </div>
</div>
