<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
			@if($create=="create_match")
            	<a class="btn btn-success fa fa-plus"  data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}} {{trans('app.ranking_eldwry')}}" href="{{ route('admin.ranking_eldwry.create') }}"></a>
            @else
            	<a class="btn btn-primary fa fa-plus"  data-toggle="tooltip" data-placement="top" data-title="{{trans('app.update')}} {{trans('app.ranking_eldwry')}}" href="{{ route('admin.ranking_eldwry.create_match') }}"></a>
			@endif
<!--             <a class="btn btn-primary fa fa-tasks" data-toggle="tooltip" data-placement="top" data-title="ranking_eldwry" href="{{ route('admin.ranking_eldwry.index') }}"></a>
            <a class="btn btn-info fa fa-search" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.search')}} ranking_eldwry" href="{{ route('admin.ranking_eldwry.search') }}"></a>
 -->
        </div>
    </div>
</div>