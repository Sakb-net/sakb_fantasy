<div class="modal fade" id="addOpta-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('app.add')}} {{$type_action}}</h4>
                </div>
                <div class="modal-body">
                    هل تريد تاكيد {{trans('app.add')}} {{$type_action}} ؟
                </div>
                <div class="modal-footer">
                    <label style="float: right;color: red; width:80%;text-align: center;">
                        يرجى عدم اجراء اى عملية بعد الضغط على الموافقة حتى تنتهى عملية الاضافة
                    </label>
                    <button type="button" class="btn btn-default" data-dismiss="modal">لا</button>
                    <button id="confirm-addOpta" type="button" class="btn btn-success">نعم</button>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '#addOpta', function(){
            $('#addOpta-modal').modal('show');
            $('#confirm-addOpta').attr('data-id', $(this).attr('data-id'));
            var title_h="{{trans('app.add')}} {{$type_action}}"+" : "+$(this).attr('data-name');
            var text_div=" هل تريد تاكيد {{trans('app.add')}}  {{$type_action}} ؟"+" : "+$(this).attr('data-name');
            $( "h4.modal-title" ).text(title_h);
            $( "div.modal-body" ).text(text_div);
        });
        
        $('body').on('click', '#confirm-addOpta', function(){ 
    
        $('#addOpta-modal').modal('hide');
        
        $('[data-addOpta-id="' + $(this).attr('data-id') + '"]').click();

    });
    
  });
  </script>
