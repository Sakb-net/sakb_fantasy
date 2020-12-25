<div id="add-player-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title Name_PLayer_mod"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="notif-msg"></div>
                        <a class="butn butn-bg w100 Modal_addPlayer" id="Modal_addPlayer">{{trans('app.add_player')}}</a>
                        
                        <a class="butn butn-bg w100 Modal_substitutePlayer" id="Modal_substitutePlayer">{{trans('app.add_player')}}</a>
                        
                    </div>
                    <div class="form-group">
                        <a class="butn butn-bg w100 popModal_infoPlayer" id="popModal_infoPlayer">{{trans('app.player_data')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>