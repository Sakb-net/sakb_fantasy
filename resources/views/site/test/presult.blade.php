 
 <div class="row draw_itttmen">
                    @include('site.test.ajax')
        </div>

        <!-- ========pagination ====-->
        <div class="styled-pagination big-pagi">
            @if(count($data)>0)
            {!! $data->render() !!}
            @endif
        </div>
        <!-- end pagination -->