<select class="SelectFormLanguage form-control">
	@foreach ($mainLanguage as $kyLang => $Langval)
    	<option value="div_{{$Langval}}">{{trans('app.'.$Langval)}}</option>
    @endforeach
</select>
<br>
<!-- selected -->