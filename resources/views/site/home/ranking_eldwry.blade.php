<div class="col-md-4">
    <div class="card">
        <h3 class="title">
            {{trans('app.ranking_eldwry')}}
             <a class="more" href="{{ route('league.index') }}">{{trans('app.more_more')}} <i class="fa fa-chevron-left"></i></a>
        </h3>
        <div id="table-wrapper">
            <div id="table-scroll">
                <table class="table text-center clubs-order">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><abbr title="مباريات ملعوبة">لعب</abbr></th>
                            <th><abbr title="فرق أهداف">فرق</abbr></th>
                            <th><abbr title="نقاط">نقاط</abbr></th>
                        </tr>
                    </thead>
                    <tbody class="draw_home_ranking_eldwry">    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>