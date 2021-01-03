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
                    <label>{{trans('app.eldwry')}} </label>
                    {!! Form::select('eldwry_id',$eldwrys ,null, array('class' => 'select2')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.slug')}} </label>
                    @if($new > 0 )
                    {!! Form::text('link', null, array('class' => 'form-control')) !!}
                    @else
                    {!! Form::text('link', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
                <div class="form-group ">
                    <label>{{trans('app.site_team')}} </label>
                    {!! Form::url('site_team', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label>{{trans('app.tags')}} </label>
                    {!! Form::select('tags[]', $tags,$teamTags, array('class' => 'select2-tags','multiple')) !!}
                </div>
                <div class="form-group">
                    <label>{{trans('app.team_image_fav')}}</label>
                    <input id="image_fav" name="image_fav" type="hidden" value="{{ $image_fav }}">
                    <img  src="{{ $image_fav }}"  width="60%" height="auto" @if($image_fav == Null)  style="display:none;" @endif />
                          @if(Auth::user()->can('access-all', 'user-all'))
                          <a href="{{URL::asset('filemanager/dialog.php?type=1&akey=admin_panel&field_id=image_fav')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @else
                    <a href="{{URL::asset('filemanager/dialog.php?type=0&akey=user&field_id=image_fav')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @endif
                    <a href="#" class="btn btn-danger fa fa-remove  remove_image_link" type="button"></a>
                </div>
                @if($team_active > 0&&$new ==0)
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