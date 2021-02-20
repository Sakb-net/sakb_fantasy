
$(document).ready(function () {

    $(document).keypress(
        function (event) {
            if (event.which == '13') {
                event.preventDefault();
            }
        });
    
    $(".startSection").hide();
    $(".mainSection").hide();
    $(".joinDraftSection").show();
    
    
    $("#startPage").click(function () {
        $(".mainSection").hide();
        $(".joinDraftSection").hide();
        $(".startSection").show();
        $("#startPage").addClass("active");
        $("#mainPage").removeClass("active");
    });
    
    $("#showStartPage,#showStartPage1,#showStartPage2").click(function(){
        $(".mainSection").hide();
        $(".joinDraftSection").hide();
        $(".startSection").show();
        $("#startPage").addClass("active");
        $("#mainPage").removeClass("active");
        });
    
    $("#mainPage").click(function () {
        $(".startSection").hide();
        $(".joinDraftSection").hide();
        $(".mainSection").show();
        $("#mainPage").addClass("active");
        $("#startPage").removeClass("active");
    });
    
    
    $("#joinDraftButton").click(function () {
        $(".startSection").hide();
        $(".joinDraftSection").show();
        $('html, body').animate({
            scrollTop: $(".game-menu").offset().top
        }, 0);
    });
    
    
    
    
    
    
    $('#joinDraft').submit(function(e){
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
    
        var form = $('#joinDraft')[0];
        var data = new FormData(form);
    
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
       $.ajax({
                url: "saveDraft",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
          success: function(response)               
           {
            if(response.status)
            window.location = response.url;
            },error: function (data) {
    
            }
          });
       });
    
    
    });