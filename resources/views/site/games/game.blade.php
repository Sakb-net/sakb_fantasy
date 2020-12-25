    <div class="game-stage">
        <!--Tab Menu Start -->
        <div class="game">
            <div class="nav" role="tablist">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link" data-toggle="tab" href="#pitch" role="tab">
                            <i class="fa fa-futbol-o"></i>{{trans('app.show_stadium')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#list" role="tab">
                            <i class="fa fa-bars"></i>{{trans('app.show_list')}}
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Tab Content Start -->
            <div class="tab-content" id="myTabContent">
                @include('site.games.master')
                @include('site.games.list_master')
            </div>
            <!-- Tab Content End -->
        </div>
        <!-- Tab Menu End -->
    </div>
    <!-- end Game-->
    @if(isset($type_page)&& $type_page=='game')
        <div class="row p20 text-center">
            <a class="butn butn-bg popModal_add_team" id="popModal_add_team">{{trans('app.enter_team')}}</a>
        </div>
    @else
        @include('site.games.btn_save')
    @endif