<div class="row">
    <div class="col-sm-9 col-md-9 col-lg-9">
        <div class="box">
            <div class="box-body">
                {!! Form::hidden('type', 'sub') !!}
                @include('admin.layouts.lang_name')
                <div class="form-group">
                    <label>{{trans('app.eldwry')}} </label>
                    {!! Form::select('eldwry_id',$eldwry ,null, array('class' => 'select2')) !!}
                </div>
                <!--  <div class="form-group hidden">
                    <label>{{trans('app.type')}} {{trans('app.subeldwry')}} </label>
                    {!! Form::select('hidden_type_id',$type_ids ,null, array('class' => 'select2')) !!}
                </div> -->
                <div class="form-group hidden">
                    <label>{{trans('app.slug')}} </label>
                    @if($new > 0 )
                    {!! Form::text('link', null, array('class' => 'form-control')) !!}
                    @else
                    {!! Form::text('link', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
                <div class="form-group row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <label>{{trans('app.date_start_end_subeldwry')}} </label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <!--'id'=>'reservation' 'id'=>'reservationtime'-->
                            {!! Form::text('date_booking', $date_booking, array('class' => 'form-control pull-right','id'=>'reservation')) !!}
                        </div>
                    </div>
                </div>
                <div class="clear-fixed margin_bot10"></div>
                <div class="form-group">
                    <label>{{trans('app.discount_change_point')}} </label>
                    {!! Form::number('change_point', $change_point, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.tags')}} </label>
                    {!! Form::select('tags[]', $tags,$subeldwryTags, array('class' => 'select2-tags','multiple')) !!}
                </div>
                @if($subeldwry_active > 0&&$new ==0)
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
            <div class="box-body">
                
                <div class="form-group">

                    <label>{{trans('app.image')}} </label>

                </div>
            </div>
        </div>
    </div>
</div>

