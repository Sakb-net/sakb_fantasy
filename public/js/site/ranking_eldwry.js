$(document).ready(function () {
    
    $('body').on('click', '.filter_ranking_eldwry', function () {
        var obj = $(this);
        var user_key = obj.attr('data-user');
        var data_link = obj.attr('data-link');
        if ((body_user_key != user_key) || user_key == '' || user_key == "") {
            $('.bi-noti-fav').html('<div class ="bi-noti-wrap show-noti alert alert-danger" role="alert" ><button class="close" aria-label="Close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button><p style="margin: 0px;">' + log_add_fav + '<a style="color:blue;" href="' + login_url + '">' + reg_login + '</a></p> </div>');
            setTimeout(function () {
                $('.show-noti').remove();
            }, 5000);
            return false;
        }
        if (typeof data_link == 'undefined' || data_link == '' || data_link == "") {
            return false;
        }
        $.ajax({
            type: "post",
            url: url + '/add_delete_fav',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                link: data_link,
                // type: 'news'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data != "") {
                    // var state_fav = data.state_action;
                   
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });
    //****************************** End comment *******************************************************************
});