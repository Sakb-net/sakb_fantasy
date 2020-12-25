<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-9 p0-sm">
                @include('site.my_team.point.slider')
<!--                 <div class="DeadlineBar">
                    <h3>الموعد النهائي لانتهاء الجولة 5: </h3>
                    <time>الجمعة 23 أغسطس 20:00</time>
                </div>
                <div class="after-DeadlineBar"></div> -->
                <!-- ====== Game =======-->
                @include('site.my_team.game')
                <!-- end Game-->
                <!--============ fixtures ================-->
                @include('site.games.fixtures')
            </div>
            <!--=======side bar ==============-->
            <div class="col-md-3">
                @include('site.my_team.side_bar') 
            </div>                           
        </div>
    </div>
</section>
    <!--==========================
    Sponsers Section
    ============================-->
@include('site.home.sponsers')
    <!-- =============================================
       player Modal
    ============================================== 
    -->
@include('site.my_team.allmodal')
