<section class="hero-area" id="slideslow-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hero-area-content">
                    @if(isset(Auth::user()->id))
                        @include('site.home.my_point')
                        <br>
                    @else
                        <h1>{{trans('app.official_game')}}<br>{{trans('app.saudi_professional_league')}}</h1>
                    @endif
                    <a href="{{ route('game.index') }}" class="butn butn-bg low_show">{{trans('app.goto_game')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>