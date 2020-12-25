<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body">
                {!! Form::hidden('type',$type) !!}
                {!! Form::hidden('typePost', $type) !!}
                <div class="form-group">
                    <label>العنوان:</label>
                    @if($type =='banner')
                    {!! Form::text('name', null, array('class' => 'form-control')) !!}
                    @else
                    {!! Form::text('name', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
                <div class="form-group hidden">
                    <label>الرابط:</label>
                    @if($new > 0 )
                    {!! Form::text('link', null, array('class' => 'form-control')) !!}
                    @else
                    {!! Form::text('link', null, array('class' => 'form-control','required'=>'')) !!}
                    @endif
                </div>
                @if($type!='banner')
                <div class="form-group">
                    <label>اختر السكشن :</label>
                    <!--ajax_get_subcategory-->
                    <select name="category_id" class="select2 type_cat" id="type_cat" required>
                        <option value="">اختر السكشن </option>
                        @foreach($categories as $key=>$val_cat)   
                        <option value="{{$val_cat->id}}">{{$val_cat->name}}</option>
                        @endforeach
                    </select>
                </div>
                @else
                {!! Form::hidden('category_id', '11') !!}
                @endif
                <div class="form-group">
                    <label>الوصف التفصيلى :</label>
                    <!--<label>نبذة مختصرة:</label>-->
                    <!--'id'=>'my-textarea'-->
                    {!! Form::textarea('content', null, array('class' => 'form-control','rows' => '2')) !!}
                </div>
                
                <div class="form-group ">
                    <label>عدد المقاعد:</label>
                    {!! Form::number('num_chart', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group hidden">
                    <label>وضعيه الصف:</label>
                    {!! Form::select('type_row',type_rowChart() ,null, array('class' => 'select2','required'=>'')) !!}
                </div>
                <div class="form-group hidden">
                    <label>رقم الصف:</label>
                    {!! Form::number('row', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label>تكلفة المقعد:</label>
                    {!! Form::number('price', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label>نسبة الخصم:</label>
                    {!! Form::number('discount', null, array('class' => 'form-control')) !!}
                </div>
                @if($type =='branch')
                <div class="form-group">
                    <label>عنوان الفرع:</label>
                    {!! Form::text('address', $address, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label>تلفون الفرع:</label>
                    {!! Form::text('phone', $phone, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label>ايميل الفرع:</label>
                    {!! Form::text('email', $email, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label>مواعيد الفرع:</label>
                    @include('admin.posts.posts.appoint')
                </div>
                @endif
                <div class="form-group hidden">
                    <label> الفديوهات:</label>
                    {!! Form::select('videos[]', $videos,$postVideos, array('class' => 'select2-tags','multiple')) !!}
                </div>
                <div class="form-group">
                    <label>الكلمات البحث:</label>
                    {!! Form::select('tags[]', $tags,$postTags, array('class' => 'select2-tags','multiple')) !!}
                </div>
                @if($new == 0 )
                @if($post_active > 0)
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
    <div class="col-sm-6 col-md-6 col-lg-6 hidden">
        <div class="box">
            <div class="box-body">
                @if($image == 1)
                <div class="form-group">
                    @if($type=='banner')
                    <label>صورة :</label><label class="padding-30"> حجم(1600*800) </label>
                    @else
                    <label>صورة :</label>
                    @endif
                    <br>
                    <input id="image" name="image" type="hidden" value="{{ $image_link }}">
                    <img  src="{{ $image_link }}"  width="40%" height="auto" @if($image_link == Null)  style="display:none;" @endif />
                          @if($post_active == 1)
                          <a href="{{URL::asset('filemanager/dialog.php?type=1&akey=admin_panel&field_id=image')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @else
                    <a href="{{URL::asset('filemanager/dialog.php?type=0&akey=user&field_id=image')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @endif
                    <a href="#" class="btn btn-danger fa fa-remove  remove_image_link" type="button"></a>

                </div>
                @endif
                @if($type !='banner' && $type !='client')
                <div class="form-group">
                    <label>الصور المرفقة :</label>
                    <label class="padding-30"> حجم(762*300) </label> 
                    @include('admin.posts.posts.repeaterImage')
                </div>
                @endif

            </div>
