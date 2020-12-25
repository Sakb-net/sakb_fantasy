<div class="row">
    <div class="col-sm-9 col-md-9 col-lg-9">
        <div class="box">
            <div class="box-body">

            @if($new ==0)
                <div class="form-group">
                    <label>{{trans('app.link')}} </label>
                    {!! Form::text('link',null , array('class' => 'form-control')) !!}
                </div>
            @endif

            @if($new ==0)
                <div class="form-group">
                    <label>{{trans('app.code')}} </label>
                    {!! Form::text('code',null , array('class' => 'form-control')) !!}
                </div>
            @endif

                <div class="form-group">
                    <label>{{trans('app.name')}} </label>
                    {!! Form::text('name', null, array('class' => 'form-control')) !!}
                </div>


                <div class="form-group">
                <label>{{trans('app.user_name')}} </label>
                {!! Form::select('user_id',$users ,null, array('class' => 'select2 ')) !!}
                </div>

                <div class="form-group">
                <label>{{trans('app.scoring_starts')}} </label>

                {!! Form::select('start_sub_eldwry_id',$subEldwry ,null, array('class' => 'select2 ','required'=>'')) !!}
                </div>


                @if($headGroupEldwry_active > 0 && $new ==0)
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
                <div class="form-group ">
                    <label>{{trans('app.image')}} </label>
                </div>
            </div>
        </div>
    </div>
</div>