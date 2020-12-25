<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                @include('admin.layouts.lang_loop')
                @include('admin.layouts.lang_name_image')
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-6 ">
        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <label>اختر الجولة:</label>
                    {!! Form::select('sub_eldwry_id',$subeldwry ,null, array('class' => 'select2')) !!}
                </div>
                <div class="form-group hidden">
                    <label>الرابط:</label>
                    @if($new > 0 )
                    {!! Form::text('link', null, array('class' => 'form-control')) !!}
                    @else
                    {!! Form::text('link', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
                <div class="clear-fixed margin_bot10"></div>
                <div class="form-group">
                    <div class="col-sm-12 col-md-7 col-lg-7">
                        <label>{{trans('app.date')}} {{trans('app.match')}}:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <!--'id'=>'reservation' 'id'=>'reservationtime'-->
                            {!! Form::text('date', null, array('class' => 'form-control pull-right','id'=>'datepicker')) !!}
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-5 col-lg-5">
                        <div class="bootstrap-timepicker">
                            <label>{{trans('app.time')}} {{trans('app.match')}}:</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                {!! Form::text('time', null, array('class' => 'form-control timepicker pull-right','id'=>'')) !!}
                            </div>                     
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="box">
            <div class="box-body ">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6 ">
                            <label>اسم الفريق الاول:</label>
                            {!! Form::select('first_team_id',$teams ,null, array('class' => 'select2')) !!}
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6 ">
                            <label>اسم الفريق الثانى:</label>
                            {!! Form::select('second_team_id',$teams ,null, array('class' => 'select2')) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6 ">
                            <label>أهداف الفريق الاول:</label>
                            {!! Form::number('first_goon', null, array('class' => 'form-control')) !!}
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6 ">
                            <label>أهداف الفريق الثانى:</label>
                            {!! Form::number('second_goon', null, array('class' => 'form-control')) !!}                        </div>
                    </div>
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.video')}} : </label>
                    {!! Form::select('video_id',$videos ,null, array('class' => 'select2')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.file')}} : </label>
                    {!! Form::select('file_id',$files ,null, array('class' => 'select2')) !!}
                </div>
                <div class="form-group hidden">
                    <label>Tage:</label>
                    {!! Form::select('tags[]', $tags,$dataTags, array('class' => 'select2-tags','multiple')) !!}
                </div>
                @if($new == 0 )
                @if($match_active > 0)
                <div class="form-group">
                    <label>الحالة:</label>
                    {!! Form::select('is_active',statusType() ,null, array('class' => 'select2','required'=>'')) !!}
                </div>
                @endif
                @endif
                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info padding-40" >حفظ</button>
                    <a href="{{$link_return}}" class="btn btn-primary padding-30">رجوع</a>
                </div>
            </div>
        </div>
    </div>
</div>
