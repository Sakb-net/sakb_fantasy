
$(document).ready(function () {


    $(document).keypress(
        function (event) {
            if (event.which == '13') {
                event.preventDefault();
            }
        });

    var selectedTeam = '';
    var selectedFollow = [];
    var checkEmail = false;
    var checkPhone = false;
    var checkCountry = true;
    var errorEmail = '';
    var errorPhone = '';

    $(".secondPage").hide();
    $(".thirdPage").hide();
    $(".email-sms").hide();
    $(".noTeams").hide();


    $(".showSecondPage").click(function () {
        console.log(checkEmail);
        console.log(checkPhone);
        $('html, body').animate({
            scrollTop: $("#progressbar").offset().top
        }, 0);

        let email = $('#email').val();
        let phone = $('#phone').val();
        let name = $('#name').val();
        let address = $('#country').val();
        let pass = $('#password').val();
        let passConfirm = $('#password-confirm').val();
        let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

        //email Validation
        if (email == '') {
            $('#errorText').text(please_enter_email);
            $("#email").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $("#email").removeClass("errorBorder");
                $('#formErorr').hide();
            }, 2000)
        }
        else if (email != '' & !emailReg.test(email)) {
            $('#errorText').text(email_not_correct);
            $("#email").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $('#formErorr').hide();
            }, 2000)
        }

        else if (checkEmail == false) {
            $("#email").addClass("errorBorder");
            $('#errorText').text(errorEmail);
            $('#formErorr').show();
            setTimeout(function () {
                $("#email").removeClass("errorBorder");
                $('#errorText').text('');
                $('#formErorr').hide();
            }, 2000)
        }

        //phone Validation
        else if (phone == '') {
            $('#errorText').text(please_enter_phone);
            $("#phone").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $("#phone").removeClass("errorBorder");
                $('#formErorr').hide();
            }, 2000)
        }
        else if (checkPhone == false) {
            $('#errorText').text(errorPhone);
            $("#phone").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $("#phone").removeClass("errorBorder");
                $('#formErorr').hide();
            }, 2000)
        }
        //name Validation
        else if (name == '') {
            $('#errorText').text(please_enter_name);
            $("#name").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $("#name").removeClass("errorBorder");
                $('#formErorr').hide();
            }, 2000)
        }
        //address Validation
        else if (address == '' && checkCountry == true) {
            $('#errorText').text(please_enter_city);
            $("#country").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $("#country").removeClass("errorBorder");
                $('#formErorr').hide();
            }, 2000)
        }
        //password Validation
        else if (pass == '') {
            $('#errorText').text(please_enter_password);
            $("#password").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $("#password").removeClass("errorBorder");
                $('#formErorr').hide();
            }, 2000)
        }
        //password length Validation
        else if (pass.trim().length < 8 || pass.trim().length > 100) {
            $('#errorText').text(password_8_100);
            $("#password").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $("#password").removeClass("errorBorder");
                $('#formErorr').hide();
            }, 2000)
        }
        //password confirm Validation
        else if (passConfirm == '') {
            $('#errorText').text(enter_password_match);
            $("#password-confirm").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $("#password-confirm").removeClass("errorBorder");
                $('#errorText').text('');
                $('#formErorr').hide();
            }, 2000)
        }
        else if (passConfirm != pass) {
            $('#errorText').text(password_not_match);
            $("#password-confirm").addClass("errorBorder");
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $("#password-confirm").removeClass("errorBorder");
                $('#formErorr').hide();
            }, 2000)
        }
        //checkbox Validation
        else if ($('#approve').prop('checked') == false) {
            $('#errorText').text(please_terms_correct);
            $('#formErorr').show();
            setTimeout(function () {
                $('#errorText').text('');
                $('#formErorr').hide();
            }, 2000)
        } else {
            $(".firstPage").hide();
            $(".secondPage").show();
            $("li#my-fav").addClass("active");
        }

    });

    $(".showThirdPage").click(function () {
        console.log(selectedTeam);
        if (selectedTeam != '') {
            $(".secondPage").hide();
            $(".thirdPage").show();
            $("li#my-follow").addClass("active");

            if (selectedFollow.length > 0) {
                $(".email-sms").show();
                for (var i = 0; i < selectedFollow.length; i++) {
                    var allArray = selectedFollow[i].split('-');
                    newRaw = "<tr id='allRows'><td><div class='radio-tile'><label class='radio-tile-label'>" + allArray[0] + "<img src=" + allArray[2] + " alt='club-logo'></div></td><td><div class='team-checkbox'><input type='checkbox' value=" + allArray[1] + " name='sms[]' id=sms" + i + "><label for=sms" + i + " class='checkbox-text'></label></div></td><td><div class='team-checkbox'><input type='checkbox'  value=" + allArray[1] + " name='emailMessages[]' id=email" + i + "><label for=email" + i + " class='checkbox-text'></label></div></td></tr>";
                    tableBody = $("table tbody");
                    tableBody.append(newRaw);
                }
            } else {
                $(".noTeams").show();
            }
        }
        $('html, body').animate({
            scrollTop: $("#progressbar").offset().top
        }, 0);

    });

    $(".backSecondPage").click(function () {
        $('table.table tr#allRows').remove();
        $('html, body').animate({
            scrollTop: $("#progressbar").offset().top
        }, 0);
        $(".thirdPage").hide();
        $(".email-sms").hide();
        $(".noTeams").hide();
        $(".secondPage").show();
        $("li#my-follow").removeClass("active");
    });

    $(".backFirstPage").click(function () {
        $('html, body').animate({
            scrollTop: $("#progressbar").offset().top
        }, 0);

        $(".secondPage").hide();
        $(".firstPage").show();
        $("li#my-fav").removeClass("active");
    });


    $(".check-button").click(function () {
        console.log('clicked')
        checkVal = $(this).val();

        const index = selectedFollow.indexOf(checkVal);
        console.log(index)
        if (index > -1) {
            selectedFollow.splice(index, 1);
        } else {
            selectedFollow.push(checkVal)
        }
        console.log(selectedFollow);
    });

    $(".radio-button").click(function () {
        selectedTeam = $("input[type='radio']:checked").val();
    });


    //*********************************validation register*******************************************************

    $('body').on('change', '#email', function () {
        var obj = $(this);
        var user_email = obj.val();
        if (user_email.trim().length == 0) return false;
        $.ajax({
            type: "post",
            url: url + '/check_found_email',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                user_email: user_email
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data != "") {
                    var response = data.response;
                    console.log(response)
                    if (response == 0) {
                        $('#errorText').text(email_already_use);
                        $("#email").addClass("errorBorder");
                        $('#formErorr').show();
                        setTimeout(function () {
                            $('#errorText').text('');
                            $("#email").removeClass("errorBorder");
                            $('#formErorr').hide();
                        }, 2000)
                        errorEmail = email_already_use;
                        checkEmail = false;
                        return false;
                    } else if (response == 2) {
                        $('#errorText').text(email_not_correct);
                        $("#email").addClass("errorBorder");
                        $('#formErorr').show();
                        setTimeout(function () {
                            $('#errorText').text('');
                            $("#email").removeClass("errorBorder");
                            $('#formErorr').hide();
                        }, 2000)
                        errorEmail = email_not_correct;
                        checkEmail = false;
                        return false;
                    } else {
                        checkEmail = true;
                        return false;
                    }

                }
            },
            complete: function (data) {
                return false;
            }
        });
        return false;
    });
    $('body').on('change', '#phone', function () {
        var obj = $(this);
        var user_phone = obj.val();
        if (user_phone.trim().length == 0) return false;
        $.ajax({
            type: "post",
            url: url + '/check_found_phone',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                user_phone: user_phone
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data != "") {
                    var response = data.response;
                    console.log(response);
                    if (response == 0) {
                        $('#errorText').text(phone_number_already_used);
                        $("#phone").addClass("errorBorder");
                        $('#formErorr').show();
                        setTimeout(function () {
                            $('#errorText').text('');
                            $("#phone").removeClass("errorBorder");
                            $('#formErorr').hide();
                        }, 2000)
                        errorPhone = phone_number_already_used;
                        checkPhone = false;
                        return false;
                    } else if (response == 2) {
                        $('#errorText').text(please_phone_correct);
                        $("#phone").addClass("errorBorder");
                        $('#formErorr').show();
                        setTimeout(function () {
                            $('#errorText').text('');
                            $("#phone").removeClass("errorBorder");
                            $('#formErorr').hide();
                        }, 2000)
                        errorPhone = please_phone_correct;
                        checkPhone = false;
                        return false;
                    } else {
                        checkPhone = true;
                        return false;
                    }
                }
            },
            complete: function (data) {
                return false;
            }
        });
        return false;
    });


    $('body').on('countrychange', function () {
        if($('#address-country').val() == 'sa'){
            $("#countryField").removeClass("hidden");
            checkCountry = true;
        }else{
            $("#countryField").addClass("hidden");
            $('#country').val('');
            checkCountry = false;
        }
    });
});
