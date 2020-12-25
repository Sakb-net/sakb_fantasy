<div id="confirm_savetransferModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close_btn close close_mod" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('app.save_change')}}</h4>
            </div>
            <div class="modal-body">
                    <div class="notif-msg"></div>
                    <div class="row">
                        <div class="form-group">
                            <div class="save-table" id="table-wrapper">
                                <div id="table-scroll">
                                    <table class="table table-bordered draw_allTransfer_save">
                                    
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="col-md-12 text-center">
                                {{trans('app.you_use_gold_or_silver_card')}}
                            </p>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <div class="notif-msg-card"></div>
                                <div class="col-md-6 col-sm-6 col-xs-6 p0">
                                    <a class="butn w100 silver confirm_cardgray">
                                   <!--  <a data-toggle="modal" data-target="#sliver-card" class="butn w100 silver"> -->
                                        <img class="btn-icon" src="
                                        {{ asset('/images/icon/Sliver-card.png')}}">
                                         {{trans('app.silver_card')}}
                                    </a>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 p0">
                                    <a class="butn w100 golden" href="{{ route('payment_card.index') }}" >
                                       <!-- confirm_cardgold data-toggle="modal" -->
                                        <img class="btn-icon" src="{{ asset('/images/icon/golden-card.png')}}">
                                         {{trans('app.gold_card')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             <div class="modal-footer">
                <!-- <a class="butn" data-dismiss="modal">{{trans('app.cancel')}}</a> -->
                <a class="butn butn-bg confirm_save_changetransfer" data-dismiss="modal">{{trans('app.confirm')}}</a>
              </div>
        </div>
    </div>
</div>