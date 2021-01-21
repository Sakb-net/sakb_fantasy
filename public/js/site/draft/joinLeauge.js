
$(document).ready(function () {
    
    $(".startSection").hide();
    $(".joinLeauge").show();
    $(".mainSection").hide();
    
    $("#startPage").click(function () {
        $(".mainSection").hide();
        $(".joinLeauge").hide();
        $(".startSection").show();
        $("#startPage").addClass("active");
        $("#mainPage").removeClass("active");
    });
    
    $("#showStartPage,#showStartPage1,#showStartPage2").click(function(){
        $(".mainSection").hide();
        $(".joinLeauge").hide();
        $(".startSection").show();
        $("#startPage").addClass("active");
        $("#mainPage").removeClass("active");
        });
    
    $("#mainPage").click(function () {
        $(".startSection").hide();
        $(".joinLeauge").hide();
        $(".mainSection").show();
        $("#mainPage").addClass("active");
        $("#startPage").removeClass("active");
    });
    
    
    $("#joinLeagueButton").click(function () {
        $(".startSection").hide();
        $(".joinLeauge").show();
        $('html, body').animate({
            scrollTop: $(".game-menu").offset().top
        }, 0);
    });
    
    
    
    $('#joinLeauge').submit(function(e){
       e.preventDefault();
    
       if ($('#condition').prop('checked') == false) {
           $('html, body').animate({
               scrollTop: $(".game-menu").offset().top
           }, 0);
               $('#errorText').text(please_terms_correct);
               $('#formErorr').show();
               setTimeout(function () {
                   $('#errorText').text('');
                   $('#formErorr').hide();
               }, 2000)
               return;
           }
        var form = $('#joinLeauge')[0];
        var data = new FormData(form);
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
       $.ajax({
                url: "joinLeauge",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
          success: function(response)               
           {
            if(response.status){
            window.location = response.url;
            }else{
    
            $('html, body').animate({
                scrollTop: $(".game-menu").offset().top
            }, 0);
                $('#errorText').text(response.msg);
                $("#code").addClass("errorBorder");
                $('#formErorr').show();
                setTimeout(function () {
                    $('#errorText').text('');
                    $("#code").removeClass("errorBorder");
                    $('#formErorr').hide();
                }, 2000)
            }
            },error: function (data) {
            }
          });
       });
    
    
    });