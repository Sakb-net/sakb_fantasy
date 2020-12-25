<div class="comments-form">
    <div class="section-title">
        <h2>{{trans('app.your_comment')}}</h2>
    </div>
    <form role="form" data-validate="parsley"  action=""  method="post" enctype="multipart/form-data"> 
        <input type="hidden" name="parent_two_id"  class="form-control parent_two_id" id="parent_two_id"/>
        <input type="hidden" name="user_ratting" class="form-control  user_ratting" id="user_ratting" min="0" max="5" />
        <div class="row">
            @guest
            <div class="col-md-12">
                <div class="form-group">
                    <input class="form-control user_name" id="user_name" name="user_name" placeholder="{{trans('app.name')}} ..." type="text" required="">
                    <div class="clearfix"></div>
                    <p class="alert alert-danger raduis comment_error_user hide" ></p>
                </div>
            </div>
            <!-- Col end -->
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" name="user_email" class="form-control  user_email" id="user_email"  required placeholder="{{trans('app.enter_your_email')}}" />
                    <div class="clearfix"></div>
                    <p class="alert alert-danger raduis comment_error_email hide" ></p>
                </div>
            </div>
            @else
            <input name="user_name" value="user_name" data-required="true" class="form-control mb-10 user_name" id="user_name" type="hidden">
            <input name="user_email" value="user@gmail.com" data-type="email" data-required="true" class="s_mail form-control mb-10 user_email" id="user_email" type="hidden">
            @endguest
            <div class="col-md-12">
                <div class="form-group">
                    <textarea class="form-control required-field user_message" name="user_message" id="user_message" placeholder="التعليق" rows="10" required=""></textarea>
                    <div class="clearfix"></div>
                    <p class="alert alert-danger raduis comment_error_content hide" ></p>
                </div>
            </div>
            <!-- Col end -->
        </div>
        <!-- Form row end -->
        <div class="clearfix">
            <button class="butn butn-bg add_post_user_message"  data-link='{{$data->link}}' id="add_post_user_message" type="submit" data-type="{{$type}}"><span>{{trans('app.send_comment')}}</span></button> 
        </div>
    </form>
    <!-- Form end -->
</div>