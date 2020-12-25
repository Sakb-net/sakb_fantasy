<div class="form-group">
    <label>{{trans('app.name')}}  <span class="star_req">*</span></label>
    <div class="row"> 
     @foreach ($mainLanguage as $kyLang => $Langval)
        <div class="col-sm-9 col-md-9 col-lg-9">
        @if(isset($array_name[$Langval]))
            {!! Form::text('lang_name[]', $array_name[$Langval], array('class' => 'form-control margin_bot10','required'=>'')) !!}
        @else
             {!! Form::text('lang_name[]', '', array('class' => 'form-control margin_bot10','required'=>'')) !!}
        @endif
        </div>
        <div class="col-sm-3 col-md-3 col-lg-3">
            <label class="btn btn-info btn-Langval" >{{trans('app.'.$Langval)}}</label> 
        </div>                   
        {!! Form::hidden('lang[]',$Langval) !!}
    @endforeach
    </div>
</div>