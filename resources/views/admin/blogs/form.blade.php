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
                {!! Form::hidden('type', 'blog') !!}
                <div class="form-group hidden">
                    <label> {{trans('app.slug')}} :</label>
                    @if($new > 0 )
                    {!! Form::text('link', null, array('class' => 'form-control')) !!}
                    @else
                    {!! Form::text('link', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
               <div class="form-group">
                    <label>Tags {{trans('app.search')}} : </label>
                    {!! Form::select('tags[]', $tags,$blogTags, array('class' => 'select2-tags','multiple')) !!}
                </div>
                <div class="form-group hidden">
                    <label> {{trans('app.color')}} :</label>
                    <div class="input-group my-colorpicker2 colorpicker-element">
                        @if(isset($color))
                        {!! Form::text('color', $color, array('class' => 'form-control my-colorpicker1 colorpicker-element','required'=>'')) !!}
                        <div class="input-group-addon">
                            <i style="background-color:{{$color}}"></i>
                        @else
                            @if($new==1)
                             {!! Form::text('color','#000', array('class' => 'form-control my-colorpicker1 colorpicker-element','required'=>'')) !!}
                            <div class="input-group-addon">
                                <i style="background-color: #000;"></i>
                            @else
                             {!! Form::text('color', null, array('class' => 'form-control my-colorpicker1 colorpicker-element','required'=>'')) !!}
                            <div class="input-group-addon">
                                <i style="background-color: "></i>
                            @endif
                        @endif
                        </div>
                    </div>
                </div>
                @if($blog_active > 0)
                    @if($new == 0)
                    <div class="form-group">
                        <label>{{trans('app.state')}} :</label>
                        {!! Form::select('is_active',statusType() ,null, array('class' => 'select2','required'=>'')) !!}
                    </div>
                    @endif
                @endif

                <div class="form-group">
                     <label>{{trans('app.team')}} :</label>
                        {!! Form::select('team_id',$teams,null, array('class' => 'select2')) !!}
                    </div>

                <div class="box-footer text-center">
                     <button type="submit" class="btn btn-info padding-40" >{{trans('app.save')}}</button>
                    <a href="{{$link_return}}" class="btn btn-primary padding-30">{{trans('app.back')}}</a>
                </div>
            </div>
        </div>
    </div>

</div>
