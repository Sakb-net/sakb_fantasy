<div class="modal fade" id="active-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('app.activeUser')}}</h4>
                </div>
                <div class="modal-body">
                   {{trans('app.activeUserQuestion')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('app.no')}}</button>
                    <button id="confirm-active" type="button" class="btn btn-success">{{trans('app.yes')}}</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deactivate-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('app.deactiveUser')}}</h4>
                </div>
                <div class="modal-body">
                  {{trans('app.deActiveUserQuestion')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('app.no')}}</button>
                    <button id="confirm-deactivate" type="button" class="btn btn-danger">{{trans('app.yes')}}</button>
                </div>
            </div>
        </div>
    </div>


    
    <div class="modal fade" id="admin-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('app.adminUser')}}</h4>
                </div>
                <div class="modal-body">
                  {{trans('app.adminUserQuestion')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('app.no')}}</button>
                    <button id="confirm-admin" type="button" class="btn btn-success">{{trans('app.yes')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="block-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('app.blockUser')}}</h4>
                </div>
                <div class="modal-body">
                  {{trans('app.blockUserQuestion')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('app.no')}}</button>
                    <button id="confirm-block" type="button" class="btn btn-danger">{{trans('app.yes')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeBlock-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('app.removeBlockUser')}}</h4>
                </div>
                <div class="modal-body">
                  {{trans('app.removeBlockUserQuestion')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('app.no')}}</button>
                    <button id="confirm-removeBlock" type="button" class="btn btn-success">{{trans('app.yes')}}</button>
                </div>
            </div>
        </div>
    </div>