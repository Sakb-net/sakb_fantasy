@extends('admin.layouts.app')
@section('title') {{trans('app.add')}}  {{trans('app.share')}}  {{trans('app.new')}}  
@stop
@section('head_content')
@stop
@section('content')



<table class="table table-bordered table-striped">
    <thead>
        <th><center>Playe Name</center></th>
        <th><center>Points</center></th>
        <th><center>Reason</center></th>
        
    </thead>
    <tbody>

        @foreach ($points as $row)
        <tr>
            <td><center>{{ $row->player->name }}</center></td>
            <td><center>{{ $row->points }}</center></td>
            <td><center>{{ $row->point->action }}</center></td>
        </tr>
        @endforeach
    </tbody>
</table>


@stop
@section('after_foot')
@stop