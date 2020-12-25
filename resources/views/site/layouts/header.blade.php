<div class="header-logos">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="logos">
                    @include('site.layouts.head_logo')
                </ul>
            </div>
        </div>
    </div>
</div>
<header class="header-wrapper">
    <div class="mymenu-wrapper">
        <div class="container">
            <div class="main-content-wrapper clearfix">
                <!-- Logo -->
                <div class="logo float-right"><a href="{{ route('home') }}"><img src="{{ asset('/uploads/logo-black.png')}}" alt="Logo"></a></div>
                <!-- ============================ Theme Menu ========================= -->
                <nav class="mymain-menu navbar float-left" id="mega-menu-wrapper">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbar-collapse-1">
                        <ul class="nav">
                            @include('site.layouts.menu')
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
                <!-- /.mymain-menu -->
            </div>
            <!-- /.main-content-wrapper -->
        </div>
        <!-- /.container -->
    </div>
    <!-- /.header-wrapper -->
</header>