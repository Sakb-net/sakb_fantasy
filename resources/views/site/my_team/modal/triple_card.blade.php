<div id="papercaptian" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('app.captain_triple_paper')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <img class="center-block" src="{{ asset('/images/icon/triple.png')}}">
                    </div>
                    <div class="form-group">
                        <p class="col-md-12 text-center"> {{trans('app.feature_captain_3_point')}}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="butn cancelTripleCard" data-dismiss="modal">{{trans('app.cancel')}}</a>
                <a class="butn butn-bg calculate_captain_triple" data-dismiss="modal">{{trans('app.confirm')}}</a>
            </div>
        </div>
    </div>
</div>