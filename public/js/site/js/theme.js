
(function($) {
    "use strict";
    
    
    $(document).on ('ready', function (){
        
        // -------------------- Navigation Scroll
        $(window).scroll(function() {   
          var sticky = $('.mymenu-wrapper'),
          scroll = $(window).scrollTop();
          if (scroll >= 190) sticky.addClass('fixed');
          else sticky.removeClass('fixed');

        });


        // ------------------------------- WOW Animation 
        var wow = new WOW({
            boxClass:     'wow',      // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset:       80,          // distance to the element when triggering the animation (default is 0)
            mobile:       true,       // trigger animations on mobile devices (default is true)
            live:         true,       // act on asynchronously loaded content (default is true)
          });
          wow.init();


        
        // -------------------- Remove Placeholder When Focus Or Click
        $("input,textarea").each( function(){
            $(this).data('holder',$(this).attr('placeholder'));
            $(this).on('focusin', function() {
                $(this).attr('placeholder','');
            });
            $(this).on('focusout', function() {
                $(this).attr('placeholder',$(this).data('holder'));
            });     
        });
        
        // -------------------- From Bottom to Top Button
            //Check to see if the window is top if not then display button
        $(window).on('scroll', function (){
          if ($(this).scrollTop() > 200) {
            $('.scroll-top').fadeIn();
          } else {
            $('.scroll-top').fadeOut();
          }
        });
            //Click event to scroll to top
        $('.scroll-top').on('click', function() {
          $('html, body').animate({scrollTop : 0},1500);
          return false;
        });

          /*----------------------------
            START - Vega slider
            ------------------------------ */
            $("#slideslow-bg").vegas({
              overlay: true,
              autoHeight: true,
              timer: false,
              transition: 'fade',
              transitionDuration: 3000,
              delay: 4000,
              color: '#000',
              //cover:true,
              animation: 'random',
            //  animationDuration: 20000,
              slides: [
                {
                  src: './images/vega-slider/banner-resized.jpg'
                },
                {
                  src: './images/vega-slider/banner2.jpg'
                }
              ]
            });
          

          // Clients carousel 
          $(".clients-carousel").owlCarousel({
              rtl: true,
              autoplay: true,
              dots: false,
              loop: true,
              responsive: {
                  0: {items: 2}, 768: {items: 3}, 900: {items: 6}
              }
          });
          $("#news-slider").owlCarousel({
              margin:20,
              rtl: true,
              autoplay: true,
              dots: true,
              loop: false,
              responsive: {
                  0: {items: 1}, 768: {items: 2}, 900: {items: 3}
              }
          });
          $("#videos-slider").owlCarousel({
              margin:20,
              rtl: true,
              autoplay: true,
              dots: true,
              loop: false,
              responsive: {
                  0: {items: 1}, 768: {items: 2}, 900: {items: 3}
              }
          });

          $('#game-week').owlCarousel({
              margin:20,
              rtl: true,
              autoplay: false,
              dots: false,
              loop: false,
              navRewind:false,
              nav:false,
              items: 1
          }); 
          $('.next-game').click(function() {
              $('#game-week').trigger('next.owl.carousel');
          })
          $('.prev-game').click(function() {
              $('#game-week').trigger('prev.owl.carousel');
          })

          $("#top-players").owlCarousel({
              margin:20,
              rtl: true,
              autoplay: false,
              dots: false,
              loop: false,
              navRewind:false,
              nav:true,
              navText: ['<span class="fa fa-chevron-left"></span>','<span class="fa fa-chevron-right"></span>'],
              responsive: {
                  0: {items: 1}, 768: {items: 3}, 900: {items: 6}
              }
          });

          //draft 3-1-2021
          ///////////////////
          $('#timepicker1').timepicker();
          $('.date-picker').datepicker({
              orientation: "top auto",
              autoclose: true
          });
          ///////////////////////

          $(".empty").click(function(){
            $('#filter').removeClass('transform');
           // $("#stretch").toggleClass("col-md-9");
           // $("#filter").toggleClass("hidden");
            
          });
           //Click to back button in pick team
          $('.back').on('click', function() {
            $('#filter').addClass('transform');
             
          });
          //$('#myModal').modal({backdrop: 'static', keyboard: false});

          $('.disabled').prop('disabled', true);

          //pagination for statistics page
          var totalpages = 8;
          $("ul.pagination_playerListStatistics li:gt('" + (totalpages) + "')").hide();
          $(".next_pag_playerList").before('<li class="page-item"><a class="page-link" href="#0">...</a></li>');
          $(".prev_pag_playerList").show();
          $(".next_pag_playerList").show();

          //pagination for add player page
          var playerpages = 3;
          $("ul.pagination_playerListLocation li:gt('" + (playerpages) + "')").hide();
          $(".prev_pag_playerList").show();
          $(".next_pag_playerList").show();

          //Event Countdown Timer
          if($('.time-countdown').length){  
            $('.time-countdown').each(function() {
            var $this = $(this), finalDate = $(this).data('countdown');
            $this.countdown(finalDate, function(event) {
              var $this = $(this).html(event.strftime(''+ '<div class="counter-column"><div class="inner"><span class="count">%S</span>Seconds</div></div>  ' + '<div class="counter-column"><div class="inner"><span class="count">%M</span>Minutes</div></div>'  + '<div class="counter-column"><div class="inner"><span class="count">%H</span>Hours</div></div> '));
            });
           });
          }
          //
          $('[data-toggle="tooltip"]').tooltip(); 
          // validation
        $('.showSecondPage').click(function(){
              let email = $('#email').val();
              let phone = $('#phone').val();
              let name = $('#name').val();
              let address = $('#country').val();
              let pass = $('#password').val();
              let passConfirm = $('#password-confirm').val();
              let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

              //email Validation
              if (email == '') {
                  $('#email').parent().append('<div class="alert alert-danger">من فضلك ادخل البريد الإلكتروني</div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              }
              if (email != '' & !emailReg.test(email)) {
                  $('#email').parent().append('<div class="alert alert-warning">صيغة البريد الإلكتروني غير صحيحة</div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              }
              //phone Validation
              if (phone == '') {
                  $('#phone').parent().append('<div class="alert alert-danger">من فضلك ادخل رقم الجوال</div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              }
              //name Validation
              if (name == '') {
                  $('#name').parent().append('<div class="alert alert-danger">من فضلك ادخل الاسم</div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              }
              //address Validation
              if (address == '') {
                  $('#country').parent().append('<div class="alert alert-danger">من فضلك اختر مدينتك </div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              }
              //password Validation
              if (pass == '') {
                  $('#password').parent().append('<div class="alert alert-danger">من فضلك ادخل كلمة المرور</div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              }
              //password confirm Validation
              if (passConfirm == '') {
                  $('#password-confirm').parent().append('<div class="alert alert-danger">من فضلك ادخل تأكيد كلمة المرور</div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              }
              if (passConfirm != pass & passConfirm != '') {
                  $('#password-confirm').parent().append('<div class="alert alert-warning">كلمة المرور غير متطابقة</div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              } 
              //checkbox Validation
              if ($('#approve').prop('checked')==false) {
                  $('#approve').parent().append('<div class="alert alert-danger">يجب قراءة الشروط والأحكام</div>');
                  setTimeout(function(){
                      $('.alert').fadeOut(2000).remove();
                  },2000)
              }

         });

        });
    
    $(window).on ('load', function (){ // makes sure the whole site is loaded

        // -------------------- Site Preloader
        $('#loader').fadeOut(); // will first fade out the loading animation
        $('#loader-wrapper').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
        $('body').delay(350).css({'overflow':'visible'});
    })

     // Smooth Scroll

      $('.mymain-menu a').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
          if (target.length) {
            $('html, body').animate({
              scrollTop: target.offset().top
            }, 1000);
            return false;
          }
        }
      });
    
})(jQuery)

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $(element).parent().append('<div class="alert text-center"><strong>تم النسخ بنجاح!</strong></div>');
    setTimeout(function(){
        $('.alert').fadeOut(200).remove();
    },2000)
  $temp.remove();
}