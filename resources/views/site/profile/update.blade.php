{!! Form::open(array('route' => 'profile.store', 'method'=>'post','data-parsley-validate'=>"")) !!}
<div class="row">
    <div class="col-md-6 col-xs-12">
        <h3 class="mg-bot-20"> {{trans('app.update')}} {{trans('app.profile')}}</h3>
        @include('site.profile.update_profile')
    </div>
    <div class="col-md-6 col-xs-12">
        <h3 class="mg-bot-20">{{trans('app.change')}} {{trans('app.password')}}</h3>
        @include('site.profile.update_password')
    </div>
</div>
{!! Form::close() !!}
