@extends('admin.layouts.app')
@section('title') {{trans('app.details')}}  
@stop
@section('head_content')
@stop
@section('content')



<table class="table table-bordered table-striped" >
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>points</th>
        <th>operations</th>

    </thead>
    <tbody>
        @if ($data->count() == 0)
        <tr>
            <td colspan="5">No data to display.</td>
        </tr>
        @endif

        @foreach ($data as $row)
        <tr>
            <td>{{ $row->user->id }}</td>
            <td>{{ $row->user->name }}</td>
            <td>{{ $row->sum }}</td>
            <td><a class="btn btn-primary fa fa-eye" data-toggle="tooltip" data-placement="top" data-title="تفاصيل "  href="{{ route('admin.subeldwry.pointsDetails',[$id,$row->user->id]) }}"></a></td>
        </tr>
        @endforeach
    </tbody>
</table>

<p>

</p>

@stop
@section('after_foot')
@stop