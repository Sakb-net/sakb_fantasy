<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                @include('admin.layouts.lang_loop')
                @include('admin.layouts.lang_name_image')
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body">
                 <div class="form-group">
                    <label>{{trans('app.team')}} </label>
                     {!! Form::select('team_id',$teams ,null, array('class' => 'select2')) !!}
                </div>
                 <div class="form-group">
                    <label> {{trans('app.location')}} </label>
                     {!! Form::select('location_id',$locations ,null, array('class' => 'select2')) !!}
                </div>
                <div class="form-group">
                    <label>{{trans('app.type_player')}} </label>
                     {!! Form::select('type_id',$type_ids ,null, array('class' => 'select2')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.slug')}} </label>
                    @if($new > 0 )
                    {!! Form::text('link', null, array('class' => 'form-control')) !!}
                    @else
                    {!! Form::text('link', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
                <div class="form-group">
                    <label>{{trans('app.price')}} </label>
                    {!! Form::number('cost', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group ">
                    <label>{{trans('app.num_t_shirt')}} </label>
                    {!! Form::number('num_t_shirt', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.tags')}}</label>
                    {!! Form::select('tags[]', $tags,$playerTags, array('class' => 'select2-tags','multiple')) !!}
                </div>
                @if($player_active > 0&&$new ==0)
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
</div>

