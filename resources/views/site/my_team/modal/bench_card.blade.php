<div id="paper3" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> {{trans('app.substitute_score_sheet')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <img class="center-block" src="{{ asset('/images/icon/bench.png')}}">
                    </div>
                    <div class="form-group">
                        <p class="col-md-12 text-center"> {{trans('app.feature_allows_15_player')}}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="butn butn cancelBenchCard" data-dismiss="modal"> {{trans('app.cancel')}}</a>
                <a class="butn butn-bg calculate_bench_players" data-dismiss="modal">{{trans('app.confirm')}}</a>
            </div>
        </div>
    </div>
</div>
