<label> {{trans('app.choose')}} {{trans('app.email')}} </label>
<select name='user_id'  class="form-control select2" required="">
    @if(isset($server))
    <option value="">{{trans('app.choose')}}</option>
    @else
    <option value="-1">{{trans('app.all_member')}}</option>
    @endif
    @if(isset($users))
        @foreach($users as $key_emai=>$val_email)
        <option value="{{$val_email->id}}">{!! str_replace('@', '@ ', $val_email->email) !!}</option>
        @endforeach
    @endif
</select>


