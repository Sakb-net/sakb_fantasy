@extends('admin.layouts.app')
@section('title') {{trans('app.langs')}} {{trans('app.page')}} @stop
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">	
                @include('admin.errors.alerts')
                <table  id="datatable"  class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{trans('app.name')}}</th>
                            <th>{{trans('app.type')}}</th>
                            <th>{{trans('app.lang')}}</th>
                            <th style="width: 350px;">{{trans('app.settings')}}</th>
                        </tr>
                    </thead>
                    @foreach ($data as $key => $page)
                    <tr>
                        <!--<td>{{ ++$key }}</td>-->
                        <td>{{ $page->id }}</td>
                        <td>{{ $page->name }}</td>
                        <td>{{ $page->type }}</td>
                        <td>{{ $page->lang }}</td>
                        <td>
                            <a class="btn btn-primary fa fa-edit btn-post" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.update')}} {{$page->lang}}" href="{{ route('admin.pages.'.$page_name.'.lang',[$page->lang_id,$page->lang]) }}"></a>
                            <?php 
                            $foundLang = App\Models\Page::DataLangAR($page->lang_id,0,-1); ?>
                            @foreach ($mainLanguage as $keymainLang => $mainLang)
                            @if(!in_array($mainLang, $foundLang))
                            <a class="btn btn-success fa fa-plus btn-post" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}} {{$mainLang}}" href="{{ route('admin.pages.'.$page_name.'.lang',[$page->lang_id,$mainLang]) }}"></a>
                            @endif
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </table>
                {{  $data->links() }}
            </div>
        </div>
    </div>
</div>
@stop
@section('after_foot')
@include('admin.pages.repeater')
@stop

