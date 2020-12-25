@include('site.games.index_master')
@include('site.home.sponsers')

@section('after_head')
@stop  
@section('after_foot')
@include('site.layouts.script.public_js')
<script>
$(document).ready(function () {
    get_dataPage(start_limit_match);
});
</script>
@stop  
