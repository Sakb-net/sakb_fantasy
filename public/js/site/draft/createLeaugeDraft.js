
$(document).ready(function () {

    $('#timepicker1').timepicker();
        $('.date-picker').datepicker({
            orientation: "top auto",
            autoclose: true
        });

$(document).keypress(
    function (event) {
        if (event.which == '13') {
            event.preventDefault();
        }
    });

$(".startSection").hide();
$(".mainSection").hide();
$(".createLeauge").show();



$("#startPage").click(function () {
    $(".mainSection").hide();
    $(".createLeauge").hide();
    $(".startSection").show();
    $("#startPage").addClass("active");
    $("#mainPage").removeClass("active");
});

$("#showStartPage,#showStartPage1,#showStartPage2").click(function(){
    $(".mainSection").hide();
    $(".createLeauge").hide();
    $(".startSection").show();
    $("#startPage").addClass("active");
    $("#mainPage").removeClass("active");
    });

    $("#mainPage").click(function () {
        $(".createLeauge").hide();
        $(".mainSection").show();
        $("#mainPage").addClass("active");
        $("#startPage").removeClass("active");
    });



$("#createLeaugeButton").click(function () {
    $(".joinDraftSection").hide();
    $(".startSection").hide();
    $(".createLeauge").show();
    $('html, body').animate({
        scrollTop: $(".game-menu").offset().top
    }, 0);
});





$('#createLeauge').submit(function(e){
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

    var form = $('#createLeauge')[0];
    var data = new FormData(form);

    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
   $.ajax({
            url: "saveLeauge",
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
                $('#formErorr').show();
                setTimeout(function () {
                    $('#errorText').text('');
                    $('#formErorr').hide();
                }, 2000)               
           }
        },error: function (data) {

        }
      });
   });
});
