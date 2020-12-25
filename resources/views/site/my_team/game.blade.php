<div class="game-stage">
    <!--Tab Menu Start -->
    <div class="game">
        <div class="nav" role="tablist">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link" data-toggle="tab" href="#pitch" role="tab">
                        <i class="fa fa-futbol-o"></i> {{trans('app.show_stadium')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#list" role="tab">
                        <i class="fa fa-bars"></i> {{trans('app.show_list')}}
                    </a>
                </li>
            </ul>
        </div>
        <!-- Tab Content Start -->
        @include('site.my_team.master')
        <!-- Single Tab Content End -->
        <!-- Single Tab Content Start -->
        @include('site.my_team.master_list')
        <!-- Single Tab Content End -->
        </div>
        <!-- Tab Content End -->
    </div>
    <!-- Tab Menu End -->
</div>