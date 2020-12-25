$(document).ready(function () {
    $('body').find('#model_msg_booking').click();    
    $('body').on('click', '.changeLanguage', function () { //change
        var obj = $(this);
        var locale = obj.attr('data-val');         // var locale = obj.val();
        $.ajax({
            type: "post",
            url: url + '/changeLanguage',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                locale: locale
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    location.reload(true);
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });
   //*****************
   //$('.empty').on('click', function() {
    //     ShowScreenTeam();
    // });
       //Click to back button in pick team
    $('.back').on('click', function() {
        BackScreenTeam();
    });
    $('body').on('click', '.tab_game_my_point', function () {
        var obj = $(this);
        var type_page ='my_point';   
        tab_menu_gameTeam(type_page,'tab_game_my_point');
        get_dataPagePoint(start_limit_match,'','','hidden');
        //reloadSliderPoint();
    });
    $('body').on('click', '.tab_game_my_team', function () {
        var obj = $(this);
        var type_page ='my_team';   
        tab_menu_gameTeam(type_page,'tab_game_my_team');
        get_dataPageTRansfer(start_limit_match,'basic','');
    });
    $('body').on('click', '.tab_game_game_transfer', function () {
        var obj = $(this);
        var type_page ='game_transfer';   
        tab_menu_gameTeam(type_page,'tab_game_game_transfer');
        get_dataPage(start_limit_match);
    });
    $('body').on('click', '.tab_game_group_eldwry', function () {
        var obj = $(this);
        var type_page ='group_eldwry';   
        tab_menu_gameTeam(type_page,'tab_game_group_eldwry');
        load_main_group_eldwry();
    });
//*********************************validation register*******************************************************
//     $('body').on('change', '.val_user_name', function () {
//         var obj = $(this);
//         var user_email = $('body').find('.db_user_email_buy').val();
//         var user_phone = $('body').find('.db_user_phone_buy').val();
//         var comment_error_email = $('.user_error_emailss');
//         var comment_error_phone = $('.user_error_phone');
//         comment_error_phone.addClass('hide');
//         comment_error_email.addClass('hide');
//         if(user_email=='' && user_phone=='' ){
//             var msgg='<p class="alert alert-danger mb-30 msg_fail"><img src="'+url+'/images/icon/fail.svg" alt="" />'+enter_email_or_phone+'</p>';
//            $('body').find('.draw_registers').html(msgg);
//        }
//         return false;
//     });

//     $('body').on('change', '.check_password_confirm', function () {
//         var obj = $(this);
//         var user_pass2 = obj.val();
//         var user_pass = obj.parent().parent().find('.user_pass_buy').val();
//         if (user_pass == undefined) {
//             user_pass = obj.parent().parent().parent().parent().find('.user_pass_buy').val();
//         }
//         var comment_error_pass = $('.user_error_pass');
//         comment_error_pass.addClass('hide');
//         if (user_pass == user_pass2) {
//             comment_error_pass.addClass('hide');
//             $('.user_pass_buy').val(user_pass);
// //            obj.val(user_pass);
//         } else {
//             comment_error_pass.removeClass('hide');
//             comment_error_pass.html(enter_password_match);
//             obj.val("");
//             $('.user_pass_buy').val("");
//             $('.user_pass_buy').focus();
//         }
//         return false;
//     });
//     $('body').on('change', '.db_user_email_buy', function () {
//         var obj = $(this);
//         var user_email = obj.val();
//         var comment_error_email = $('.user_error_emailss');
// //        var comment_error_phone = $('.user_error_phone');
//         comment_error_email.addClass('hide');
// //        comment_error_phone.addClass('hide');
//         var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
// //        if (user_email == '' || re.test(user_email) != true) {
// //            comment_error_email.removeClass('hide');
// //            comment_error_email.html(email_not_correct);
// //            $('.user_email_buy').focus();
// //            return false;
// //        }
//         $.ajax({
//             type: "post",
//             url: url + '/check_found_email',
//             data: {
//                 _token: $('meta[name="_token"]').attr('content'),
//                 user_email: user_email
//             },
//             cache: false,
//             dataType: 'json',
//             success: function (data) {
//                 if (data != "") {
// //                if (data.trim() != "") {
//                     var response = data.response;
//                     if (response == 1) {
//                         comment_error_email.addClass('hide');
//                         $('.user_email_buy').val(user_email);
//                         return false;
//                     } else if (response == 2) {
//                         comment_error_email.removeClass('hide');
//                         comment_error_email.html('( ' + user_email + ' ) ' + email_not_correct + '');
//                         $('.user_email_buy').val(" ");
//                         $('.db_user_email_buy').focus();
//                         return false;
//                     } else {
//                         comment_error_email.removeClass('hide');
//                         comment_error_email.html('( ' + user_email + ' ) ' + email_already_use + '');
//                         $('.user_email_buy').val(" ");
//                         $('.db_user_email_buy').focus();
//                         return false;
//                     }

//                 }
//             },
//             complete: function (data) {
//                 return false;
//             }});
//         return false;
//     });
//     $('body').on('change', '.db_user_phone_buy', function () {
//         var obj = $(this);
//         var user_phone = obj.val();
//         var comment_error_phone = $('.user_error_phone');
// //        comment_error_email.addClass('hide');
//         comment_error_phone.addClass('hide');
//         if (typeof user_phone == 'undefined' || user_phone == '' || user_phone == "") {
//             comment_error_phone.removeClass('hide');
//             comment_error_phone.html(please_phone_correct);
//             $('.db_user_phone_buy').focus();
//             return false;
//         }
//         if (user_phone == 0 || user_phone == "0" || user_phone == '0') {
//             return false;
//         }
//         $.ajax({
//             type: "post",
//             url: url + '/check_found_phone',
//             data: {
//                 _token: $('meta[name="_token"]').attr('content'),
//                 user_phone: user_phone
//             },
//             cache: false,
//             dataType: 'json',
//             success: function (data) {
//                 if (data != "") {
// //              if (data.trim() != "") {
//                     var response = data.response;
//                     if (response == 1) {
//                         comment_error_phone.addClass('hide');
//                         $('.user_phone_buy').val(user_phone);
//                         return false;
//                     } else if (response == 2) {
//                         comment_error_phone.removeClass('hide');
//                         comment_error_phone.html('( ' + user_phone + ' )  ' + please_phone_correct + '');
//                         $('.user_phone_buy').val(" ");
//                         $('.db_user_phone_buy').focus();
//                         return false;
//                     } else {
//                         comment_error_phone.removeClass('hide');
//                         comment_error_phone.html('( ' + user_phone + ' )  ' + phone_number_already_used + '');
//                         $('.user_phone_buy').val(" ");
//                         $('.db_user_phone_buy').focus();
//                         return false;
//                     }
//                 }
//             },
//             complete: function (data) {
//                 return false;
//             }});
//         return false;
//     });
    // add frist step to buy if not register
    $('body').on('click', '.add_register_buy', function () {
        var obj = $(this);
        var user_name = $(this).parent().parent().parent().parent().find('#user_name_buy').val();
        var user_terms = $(this).parent().parent().parent().parent().find('#user_terms_condition:checked').val();
        var user_email = $(this).parent().parent().parent().parent().find('#user_email_buy').val();
        var user_phone = $(this).parent().parent().parent().parent().find('#user_phone_buy').val();
        var user_pass = $(this).parent().parent().parent().parent().find('.user_pass_buy').val();
        var user_pass2 = $(this).parent().parent().parent().parent().find('#check_password_confirm').val();
//        var user_country = $("#country option:selected").val();
        var order_link = $(this).parent().parent().parent().parent().find('#current_order_buy').val();
        var comment_error_name = $('.user_error_namess');
        var comment_error_email = $('.user_error_emailss');
        var comment_error_pass = $('.user_error_pass');
        var comment_error_phone = $('.user_error_phone');
        var comment_error_terms = $('.user_error_terms');
        comment_error_pass.addClass('hide');
        comment_error_name.addClass('hide');
        comment_error_email.addClass('hide');
        comment_error_phone.addClass('hide');
        comment_error_terms.addClass('hide');
        if (typeof user_name == 'undefined' || user_name == '' || user_name == "") {
            comment_error_name.removeClass('hide');
            if (!comment_error_email.hasClass('hide')) {
                comment_error_email.addClass('hide');
            }
            if (!comment_error_pass.hasClass('hide')) {
                comment_error_pass.addClass('hide');
            }
            if (!comment_error_phone.hasClass('hide')) {
                comment_error_phone.addClass('hide');
            }
            if (!comment_error_terms.hasClass('hide')) {
                comment_error_terms.addClass('hide');
            }
            comment_error_name.html(please_enter_name);
            $('.user_name_buy').focus();
            return false;
        }
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (typeof user_email == 'undefined' || user_email == '' || re.test(user_email) != true) {
            comment_error_email.removeClass('hide');
            if (!comment_error_pass.hasClass('hide')) {
                comment_error_pass.addClass('hide');
            }
            if (!comment_error_name.hasClass('hide')) {
                comment_error_name.addClass('hide');
            }
            if (!comment_error_phone.hasClass('hide')) {
                comment_error_phone.addClass('hide');
            }
            if (!comment_error_terms.hasClass('hide')) {
                comment_error_terms.addClass('hide');
            }
            comment_error_email.html(email_not_correct);
            $('.user_email_buy').val(" ");
            $('.db_user_email_buy').focus();
            return false;
        }
        var pho = /^([+]?)[0-9]{8,16}$/; // /^[0-9]{8,16}$/      ///^(\+91-|\+91|0)?\d{10}$/
        if (typeof user_phone == 'undefined' || user_phone == '' || user_phone == "" || pho.test(user_phone) != true) {
            comment_error_phone.removeClass('hide');
            if (!comment_error_email.hasClass('hide')) {
                comment_error_email.addClass('hide');
            }
            if (!comment_error_pass.hasClass('hide')) {
                comment_error_pass.addClass('hide');
            }
            if (!comment_error_name.hasClass('hide')) {
                comment_error_name.addClass('hide');
            }
            if (!comment_error_terms.hasClass('hide')) {
                comment_error_terms.addClass('hide');
            }
            comment_error_phone.html(please_phone_correct);
            $('.user_phone_buy').val(" ");
            $('.db_user_phone_buy').focus();
            return false;
        }
        if (user_pass != user_pass2) {
            comment_error_pass.removeClass('hide');
            if (!comment_error_email.hasClass('hide')) {
                comment_error_email.addClass('hide');
            }
            if (!comment_error_name.hasClass('hide')) {
                comment_error_name.addClass('hide');
            }
            if (!comment_error_phone.hasClass('hide')) {
                comment_error_phone.addClass('hide');
            }
            if (!comment_error_terms.hasClass('hide')) {
                comment_error_terms.addClass('hide');
            }
            comment_error_pass.html(enter_password_match);
            obj.val("");
            $('.user_pass_buy').val("");
            $('.user_pass_buy').focus();
            return false;
        }
        if (typeof user_terms == 'undefined' || user_terms != 'on' || user_terms != "on" || user_terms == 0 || user_terms == "0" || user_terms == '0') {
            comment_error_terms.removeClass('hide');
            if (!comment_error_email.hasClass('hide')) {
                comment_error_email.addClass('hide');
            }
            if (!comment_error_pass.hasClass('hide')) {
                comment_error_pass.addClass('hide');
            }
            if (!comment_error_name.hasClass('hide')) {
                comment_error_name.addClass('hide');
            }
            if (!comment_error_phone.hasClass('hide')) {
                comment_error_phone.addClass('hide');
            }
            comment_error_terms.html(please_terms_correct);
            $('.user_terms_condition').focus();
            return false;
        }
        $.ajax({
            type: "post",
            url: url + '/add_register_buy',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                name: user_name,
                email: user_email,
                password: user_pass,
                password_confirmation: user_pass2,
                phone: user_phone,
                terms: user_terms,
                order_link: order_link
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data != "") {
                    var response = data.response;
                    var status = data.status;
                    var buy = data.buy;
                    $(".draw_payment_step").text('');
                    $(".draw_payment_step").html(response);
                    if (status == 1) {
                        //$('body').find('.buy-desc').html('');
                    }
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });
    //******************************** upload user Image profile and add***************************************
    $('body').on('click', '.add_image', function () {
        $('.change_photo_input').click();
    });
    $('.change_photo_input').change(function () {
        var progress = $('.progress');
        var progress_bar = $('.progress-bar');
        var thumbnail = $('.member_image_update');
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader)
            return; // no file selected, or no FileReader support
        if (/^image/.test(files[0].type)) { // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
            reader.onloadend = function () { // set image data as background of div
                // handel file before upload
                var data = new FormData();
                $.each(files, function (key, value) {
                    data.append(key, value);
                });
                progress.removeClass('hide');
                data.append('_token', $('meta[name="_token"]').attr('content'));
                data.append('yes_compress', 1);
                $.ajax({
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                progress_bar.css({
                                    width: percentComplete * 100 + '%'
                                });
                                progress_bar.text(
                                        percentComplete * 100 + '%'
                                        );
                            }
                        }, false);
                        xhr.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                progress_bar.css({
                                    width: percentComplete * 100 + '%'
                                });
                                progress_bar.text(
                                        percentComplete * 100 + '%'
                                        );
                            }
                        }, false);
                        return xhr;
                    },
                    url: url + '/add_image_user',
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function (data) {
                        var s = data.response;
                        if (Math.abs(s) == 1) {
                            progress.addClass('hide');
                            $('.img-addcir').removeClass('img-addcir');
                            thumbnail.attr('src', data.path);
                            $('.bi-noti').html('<div class ="bi-noti-wrap show-noti alert alert-success alert-dismissible" role="alert" ><button class="close" aria-label="Close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button><p>' + chang_img_profile_scuss + '</p> </div>');
                            setTimeout(function () {
                                $('.show-noti').remove();
                            }, 5000);
                        } else {
                            progress.addClass('hide');
                            $('.bi-noti').html('<div class ="bi-noti-wrap show-noti alert alert-success alert-dismissible" role="alert" ><button class="close" aria-label="Close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button><p>' + chang_img_profile_false + '</p> </div>');
                            setTimeout(function () {
                                $('.show-noti').remove();
                            }, 50000);
                        }
                    }
                });
            }
        }
    });
    //*********************************************************************
    $('body').on('click', '.add_contact_Us', function () {
        var obj = $(this);
        var user_message = $('.user_message').val();
        var user_email = $('.user_email').val();
        var user_name = $('.user_name').val();
        var type_message = $('.type_message').val();
        var comment_error_user = $('.comment_error_user');
        var comment_error_email = $('.comment_error_email');
        var comment_error_content = $('.comment_error_content');
        comment_error_user.addClass('hide');
        comment_error_email.addClass('hide');
        comment_error_content.addClass('hide');
        if (user_name == '') {
            comment_error_user.removeClass('hide');
            if (!comment_error_email.hasClass('hide')) {
                comment_error_email.addClass('hide');
            }
            if (!comment_error_content.hasClass('hide')) {
                comment_error_content.addClass('hide');
            }
            comment_error_user.html(please_enter_name);
            $('.user_name').focus();
            return false;
        }
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (user_email == '' || re.test(user_email) != true) {
            comment_error_email.removeClass('hide');
            if (!comment_error_user.hasClass('hide')) {
                comment_error_user.addClass('hide');
            }
            if (!comment_error_content.hasClass('hide')) {
                comment_error_content.addClass('hide');
            }
            comment_error_email.html('' + email_not_correct + '(ex: aaa@gmail.com)');
            $('.user_email').focus();
            return false;
        }
        if (user_message == '') {
            comment_error_content.removeClass('hide');
            if (!comment_error_user.hasClass('hide')) {
                comment_error_user.addClass('hide');
            }
            if (!comment_error_email.hasClass('hide')) {
                comment_error_email.addClass('hide');
            }
            comment_error_content.html(please_enter_comment);
            $('.user_message').focus();
            return false;
        }
        $.ajax({
            type: "post",
            url: url + '/add_contact_Us',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                content: user_message,
                name: user_name,
                email: user_email,
                type: type_message
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data != "") {
                    var state_add = data.state_add;
//                    if (state_add == 1) {
//                        $('.user_comment_focus').focus();
                    $('.user_message').focus();
                    $('#user_message').val('');
                    if (body_user_key == '') {
                        $('#user_email').val('');
                        $('#user_name').val('');
                    }
                    $('.stat_questions_found').addClass('hide');
//                    } else {
                    $("#draw_correct_wrong").text('');
                    $("#draw_correct_wrong").html(data.response);
//                    }
                    $('body').find('#parent_two_id').val('');
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });
    //******************************************************
});

//*********End
function tab_menu_gameTeam(type_page,active_class){ 
    $('body').find('.tab_menu_game').removeClass('active');
    $('body').find('.'+active_class).addClass('active');
    $.ajax({
        type: "post",
        url: url + '/tab_menu_gameTeam',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            type_page: type_page
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                if(data.redirect_route!=''){
                    window.location.href=url+'/' + data.redirect_route;
                }else{
                    history.pushState('data to be passed', 'Title of the page', data.current_url_page);
                    $('body').find('.Draw_tab_game_transfer').text('');
                    $('body').find('.Draw_tab_game_transfer').html(data.response);
                }
                //location.reload(true);
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}

function info_dataDiv(msg=''){ 
    var empty_div='<div class="alert alert-info alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>';
    // var empty_div='<div class="alert alert-info alert-dismissible">'+msg+'</div>';
    return empty_div;
}
function success_dataDiv(add_msg=''){
    if(add_msg==''){
      add_msg = add_scuss;
    }
    var empty_div='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+add_msg+'</div>';
    return empty_div;
}
function fail_dataDiv(fail_msg=''){
    if(fail_msg==''){
       fail_msg=add_fail;  //add_fail_info
    }
    var empty_div='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+fail_msg+'</div>';
    return empty_div;
}

function delete_dataDiv(delet_msg=''){
    if(delet_msg==''){
       delet_msg=add_delete;  //add_fail_info
    }
    var empty_div='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+delet_msg+'</div>';
    return empty_div;
}
function ex_ajax(offset=1){
    $.ajax({
        type: "post",
        url: url + '/ajax_pagination',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            offset: offset,
            flage: 0
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
               $('body').find('#tag_container').text('');
                $('body').find('#tag_container').html(data.response);
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function ex_ajaxPagination(offset=1){
    $.ajax({
        type: "post",
        url: url + '/ajax_pagination',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            offset: offset,
            flage: 1
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
               $('body').find('.draw_itttmen').text('');
                $('body').find('.draw_itttmen').html(data.response);
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}