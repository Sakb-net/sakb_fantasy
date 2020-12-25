@php $index_key=0; @endphp
@foreach ($mainLanguage as $kyLang => $Langval)
<div class="FormLanguage div_{{$Langval}} @if($index_key!=0) hidden @endif">	
	<div class="form-group">
	    <label>{{trans('app.name')}}   :<span class="star_req">*</span></label>
	    <!-- ,'required'=>'' -->
	    @if(isset($array_name[$Langval]))
	    	{!! Form::text('lang_name[]', $array_name[$Langval], array('class' => 'form-control')) !!}
	    @else
	    	{!! Form::text('lang_name[]', '', array('class' => 'form-control')) !!}
	    @endif
	</div>
	@if(isset($show_description))
	<div class="form-group">
		<label>{{trans('app.abstract')}}  :</label>
		@if(isset($array_description[$Langval]))
	    	{!! Form::textarea('description[]',$array_description[$Langval], array('class' => 'form-control','rows' => '2')) !!}
	    @else
	    	{!! Form::textarea('description[]', '', array('class' => 'form-control','rows' => '2')) !!}
	    @endif

	</div>
	@endif
	@if(isset($show_content))
	<div class="form-group">
	    <!-- ,'id'=>'my-textarea' -->
	    <label>{{trans('app.detail_desc')}} :</label>
	    @if(isset($array_content[$Langval]))
	    	{!! Form::textarea('content[]', $array_content[$Langval], array('class' => 'form-control')) !!}
	    @else
	    	{!! Form::textarea('content[]', '', array('class' => 'form-control')) !!}
	    @endif
	</div>
	@endif
	@if($image == 1)
	<div class="form-group">
	    <label>{{trans('app.image')}} :</label>
	    <br>
	    @if(isset($array_image[$Langval]))
	    	<input id="image{{$index_key}}" name="image[]" type="hidden" value="{{ $array_image[$Langval] }}">
	    	<img  src="{{ $array_image[$Langval] }}"  width="40%" height="auto" @if($array_image[$Langval] == Null)  style="display:none;" @endif />
	    @else
	    	<input id="image{{$index_key}}" name="image[]" type="hidden" value="{{ $image_link }}">
	    	<img  src="{{ $image_link }}"  width="40%" height="auto" @if($image_link == Null)  style="display:none;" @endif />
	   	@endif
        @if(Auth::user()->can('access-all', 'user-all'))
	    <a href="{{URL::asset('filemanager/dialog.php?type=1&akey=admin_panel&field_id=image'.$index_key)}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
	    @else
	    <a href="{{URL::asset('filemanager/dialog.php?type=0&akey=user&field_id=image'.$index_key)}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
	    @endif
	    <a href="#" class="btn btn-danger fa fa-remove  remove_image_link" type="button"></a>
	</div>
	@endif
{!! Form::hidden('lang[]',$Langval) !!}
@php $index_key +=1; @endphp
</div>
@endforeach	 