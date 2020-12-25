
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-right image">
                @if($user_account->image != NULL)
                <img src="{{ $user_account->image }}" class="img-circle" alt="User Image">
                @else
                <img src="{{ asset('css/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                @endif
            </div>
             <div class="pull-right info">
                <p>{{ $user_account->display_name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                <select class="changeLanguage" style="background: #34585a;">
                    <option value="ar" @if($cuRRlocal == 'ar') selected  @endif >{{trans('app.Arabic')}}</option>
                    <option value="en" @if($cuRRlocal == 'en') selected  @endif >{{trans('app.English')}}</option>
                </select>
            </div>
        </div>
        <ul class="sidebar-menu">
            @if($category_all == 1)

            <!--            <li class="treeview">
                            <a href="">
                                <i class="fa fa-home"></i> <span>الرئيسية</span> 
                                <i class="fa fa-angle-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('admin.pages.home') }}">
                                        <i class="fa fa-image"></i> <span>الصورة الخلفية</span> 
                                    </a></li>
                            </ul>
                        </li>-->
             <li class="treeview">
             <a href="/translations" target="_blank">
                <i class="fa fa-language"></i><span>{{trans('app.translations_website')}}</span>
            </a>
            </li>
             <li class="treeview">
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>OPTA/FANTASY  DATA</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.opta.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} OPTA DATA</span> 
                        </a></li>
                        <li><a href="{{ route('admin.opta.creat','result_subeldwry') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.result_subeldwry')}}</span> 
                        </a></li>
                        <li><a href="{{ route('admin.opta.creat','result_matche') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.result_matche')}} </span> 
                        </a></li>
                        <li><a href="{{ route('admin.opta.creat','transfer_player') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.transfer_player_improvepoints')}} </span> 
                        </a></li>
                        <li><a href="{{ route('admin.opta.creat','all') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.all')}} OPTA DATA</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.fantasy.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} FANTASY DATA</span> 
                        </a></li> -->

<!--                     <li><a href="{{ route('admin.opta.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}}</span> 
                        </a></li> -->
                </ul>
            </li>            
            <li class="treeview">
                <a href="">
                    <i class="fa fa-sitemap"></i> <span>{{trans('app.eldwry')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.eldwry.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.eldwry')}} </span> 
                        </a></li>
                    <li><a href="{{ route('admin.eldwry.index') }}">
                            <i class="fa fa-cubes"></i> <span> كل {{trans('app.eldwry')}} </span> 
                        </a></li>

                    <!-- <li><a href="{{ route('admin.subeldwry.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}} {{trans('app.eldwry')}}</span> 
                        </a></li> -->
                </ul>
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-cube"></i> <span>{{trans('app.subeldwrys')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.subeldwry.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.subeldwry')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.subeldwry.index') }}">
                            <i class="fa fa-cube"></i> <span>{{trans('app.subeldwrys')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.subeldwry.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}} {{trans('app.subeldwrys')}}</span> 
                        </a></li> -->
                </ul>
            </li>
            <!--end eldwry-->


            <li class="treeview">
                <a href="">
                    <i class="fa fa-cubes"></i> <span>{{trans('app.teams')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.clubteams.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}}  {{trans('app.team')}} </span> 
                        </a></li>
                    <li><a href="{{ route('admin.clubteams.index') }}">
                            <i class="fa fa-cubes"></i> <span>  {{trans('app.teams')}} </span> 
                        </a></li>
                    <!--                    <li><a href="{{ route('admin.subcategories.create') }}">
                                                <i class="fa fa-plus-square"></i> <span>اضافة سكشن فرعى</span> 
                                            </a></li>
                                        <li><a href="{{ route('admin.subcategories.index') }}">
                                                <i class="fa fa-cube"></i> <span> السكشن الفرعية</span> 
                                            </a></li>-->
                    <!-- <li><a href="{{ route('admin.allclubteams.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}}  {{trans('app.teams')}}</span> 
                        </a></li> -->
                </ul>
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-support"></i> <span>{{trans('app.players')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.players.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.player')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.players.index') }}">
                            <i class="fa fa-support"></i> <span> {{trans('app.players')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.players.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}} {{trans('app.players')}}</span> 
                        </a></li>   -->
                </ul>
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-circle"></i> <span>{{trans('app.lineups')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.settings.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.lineup')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.settings.index') }}">
                            <i class="fa fa-circle"></i> <span> {{trans('app.lineups')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.settings.search','lineup') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}} {{trans('app.lineups')}}</span> 
                        </a></li>   -->
                </ul>
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-cube"></i> <span>{{trans('app.matches')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.matches.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.match')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.matches.index') }}">
                            <i class="fa fa-cube"></i> <span> {{trans('app.matches')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.matches.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}} {{trans('app.matches')}}</span> 
                        </a></li>   -->
                </ul>
            </li>
            
            <li class="treeview">
                <a href="">
                    <i class="fa fa-cubes"></i> <span>{{trans('app.groupEldwry')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.groupEldwry.index') }}">
                            <i class="fa fa-cubes"></i> <span>  {{trans('app.eldwry')}} (Classic)</span> 
                        </a></li>
                    <li><a href="{{ route('admin.headGroupEldwry.index') }}">
                            <i class="fa fa-cubes"></i> <span>  {{trans('app.eldwry')}} (Head To Head)</span> 
                        </a></li>

<!-- 
                        <a href="{{ route('admin.groupEldwry.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}}  {{trans('app.groupEldwry')}} </span> 
                        </a></li> -->

                </ul>
            </li>
             <!--end groupEldwry-->
            @endif
            @if($post_all == 1)
            <li class="treeview">
                <a href="">
                    <i class="fa fa-file-video-o"></i> <span>{{trans('app.videos')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.videos.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.video')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.videos.index') }}">
                            <i class="fa fa-file-video-o"></i> <span>  {{trans('app.videos')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.videos.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}}  {{trans('app.videos')}}</span> 
                        </a></li> -->
                    <li><a href="{{ route('admin.videocomments.index') }}">
                            <i class="fa fa-comment"></i> <span> {{trans('app.comments')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.videocomments.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}} {{trans('app.comments')}}</span> 
                        </a></li>      -->
                </ul>
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-newspaper-o"></i> <span>{{trans('app.news')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.blogs.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add_news')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.blogs.index') }}">
                            <i class="fa fa-newspaper-o"></i> <span> {{trans('app.news')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.blogs.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search_news')}}</span> 
                        </a></li> -->
                    <li><a href="{{ route('admin.blogcomments.index') }}">
                            <i class="fa fa-comment"></i> <span> {{trans('app.comments')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.blogcomments.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}} {{trans('app.comments')}}</span> 
                        </a></li>      -->
                </ul>
            </li>
            @if($comment_all == 1)
            <!--            <li class="treeview">
                            <a href="">
                                <i class="fa fa-comment"></i> <span>التعليقات</span> 
                                <i class="fa fa-angle-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('admin.comments.create') }}">
                                        <i class="fa fa-plus-square"></i> <span>اضافة تعليق </span> 
                                    </a></li>
                                <li><a href="{{ route('admin.comments.index') }}">
                                        <i class="fa fa-comment"></i> <span> التعليقات</span> 
                                    </a></li>
                                <li><a href="{{ route('admin.comments.search') }}">
                                        <i class="fa fa-search"></i> <span>بحث التعليقات</span> 
                                    </a></li>
                            </ul>
                        </li>-->
            @endif
            @endif
            @if($access_all == 1)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-asterisk"></i> <span>{{trans('app.settings_site')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.options') }}">
                            <i class="fa fa-asterisk"></i> <span>{{trans('app.settings_public')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.option_time') }}">
                            <i class="fa fa-asterisk"></i> <span>{{trans('app.period_time_stop_subeldwry')}}</span> 
                        </a></li>   
                    <li><a href="{{ route('admin.pages.about') }}">
                            <i class="fa fa-support"></i> <span>{{$about_title}}</span> 
                        </a></li>
                      <li><a href="{{ route('admin.pages.instractions') }}">
                            <i class="fa fa-support"></i> <span>{{$instraction_title}}</span> 
                        </a></li>    
                    <li><a href="{{ route('admin.pages.awards') }}">
                        <i class="fa fa-support"></i> <span>{{$award_title}}</span>
                    </a></li> 
                        
                    <li><a href="{{ route('admin.pages.contact') }}">
                            <i class="fa fa-envelope"></i> <span> {{$contact_title}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.pages.terms') }}">
                            <i class="fa fa-rocket"></i> <span> {{$terms_title}}</span> 
                        </a></li>
                    <!--all pages site-->
                </ul>
            </li>
            @endif
            <li class="treeview">
                <a href="">
                    <i class="fa fa-language"></i> <span>{{trans('app.site_languages')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.languages.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add_language')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.languages.index') }}">
                            <i class="fa fa-language"></i> <span>{{trans('app.site_languages')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.languages.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search_language')}}</span> 
                        </a></li> -->
                </ul>
            </li>
            @if($contact_all == 1)
            <li class="treeview">
                <a href="{{ route('admin.contacts.type','contact') }}">
                    <i class="fa fa-envelope"></i> <span>{{trans('app.message')}}  {{$contact_title}}</span> 
                </a>
            </li>
            @endif
            <li class="treeview hide">
                <a href="">
                    <i class="fa fa-eercast"></i> <span>رسائل الموقع والموبايل </span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.apimessages.create') }}">
                            <i class="fa fa-plus-square"></i> <span>اضافة جديدة</span> 
                        </a></li>
                    <li><a href="{{ route('admin.apimessages.index') }}">
                            <i class="fa fa-eercast"></i> <span>رسائل الموقع والموبايل </span> 
                        </a></li>
                    <li><a href="{{ route('admin.apimessages.search') }}">
                            <i class="fa fa-search"></i> <span>بحث رسائل الموقع والموبايل  </span> 
                        </a></li>
                </ul>
            </li>
            @if($user_all == 1)
            <li class="treeview">
                <a href="">
                    <i class="fa fa-group"></i> <span>{{trans('app.members')}}</span> 
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @if($access_all == 1)
                    <li><a href="{{ route('admin.permission.create') }}">
                            <i class="fa fa-plus-square"></i> <span>اضافة صلاحية</span> 
                        </a></li>
                    <li><a href="{{ route('admin.roles.create') }}">
                            <i class="fa fa-plus-square"></i> <span>اضافة وظيفة</span> 
                        </a></li>
                    <li><a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-user-secret"></i> <span> وظائف الاعضاء</span> 
                        </a></li>
                    @endif
                    <li><a href="{{ route('admin.users.create') }}">
                            <i class="fa fa-plus-square"></i> <span>{{trans('app.add')}} {{trans('app.member')}}</span> 
                        </a></li>
                    <li><a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-users"></i> <span>كل {{trans('app.members')}}</span> 
                        </a></li>
                    <!-- <li><a href="{{ route('admin.users.search') }}">
                            <i class="fa fa-search"></i> <span>{{trans('app.search')}} {{trans('app.members')}}</span> 
                        </a></li> -->
                </ul>
            </li>
            @endif
            @if($access_all == 1)
            <li class=" treeview">
                <!--{{ route('admin.index') }}-->
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>{{trans('app.statistics_reports')}}</span>
                    <i class="fa fa-angle-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.statisticsusers') }}">
                            <i class="fa fa-users"></i> <span>{{trans('app.customer_stats')}} </span> 
                        </a></li>
                </ul>
            </li>

            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
