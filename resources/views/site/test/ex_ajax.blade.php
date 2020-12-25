@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="section-padding wow fadeInUp">
    <div class="container" id="tag_container">
       @include('site.test.presult')
    </div>
</section>
@include('site.home.sponsers')
@endsection

@section('after_head')
@stop  
@section('after_foot')
<script type="text/javascript">
    $(window).on('hashchange', function() {
        if (window.location.hash) {
                    console.log('hashchange');
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            }else{
                getData(page);
            }
        }
    });
    $(document).ready(function(){
        ex_ajax(1);
        $(document).on('click', '.pagination span',function(event) {
            var obj = $(this);
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            offset=1;
            ex_ajaxPagination(offset);
        });
    $(document).on('click', '.pagination a',function(event) {
        var obj = $(this);
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var url_pag=obj.attr('href');
        var url = new URL(url_pag);
        var offset = url.searchParams.get("page");
        // *********
        next_page=offset;
        next_page++;
        url_bn_next=url.origin+url.pathname+'?page='+next_page;
        // *********
        console.log(offset);
        ex_ajaxPagination(offset);
    });
});
</script>

@stop  
