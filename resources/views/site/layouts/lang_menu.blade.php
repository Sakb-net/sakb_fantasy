<li class="dropdown-holder">
    <a class="english" data-val="{{$cuRRlocal}}"><i class="fa fa-language"></i>{!!trans('app.'.$cuRRlocal)!!}</a>
    <ul class="sub-menu">
        @foreach($languages as $keypost=>$val_data)
            @if($cuRRlocal != $val_data->lang)
            <li class="changeLanguage" data-val="{{$val_data->lang}}"><a class="changeLanguage" data-val="{{$val_data->lang}}">{!!trans('app.'.$val_data->lang)!!}</a></li>
            @endif
        @endforeach
    </ul>
    <i class="fa fa-caret-down icon" aria-hidden="true"></i>
</li>
