@if ($message_success = Session::get('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-check"></i>{{ $message_success }}</h4>
</div>
@endif

@if ($message_info = Session::get('info'))
<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-info"></i>{{ $message_info }}</h4>
    
</div>
@endif

@if ($message_warning = Session::get('warning'))
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-warning"></i>{{ $message_warning }}</h4>
    
</div>
@endif        

@if ($message_error = Session::get('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-ban"></i>{{ $message_error }}</h4>
</div>
@endif


<div class="alert alert-danger alert-dismissible" id="msg_div" style="display:none">
    <h4 id="res_message"><i class="icon fa fa-ban"></i></h4>
</div>

@php(session()->forget('info'))
@php(session()->forget('warning'))
@php(session()->forget('error'))
@php(session()->forget('success'))
