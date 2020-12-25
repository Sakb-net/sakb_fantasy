@extends('admin.layouts.app')
@section('title') {{trans('app.players')}}
@stop
@section('head_content')

<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body ">
                @include('admin.layouts.lang_loop')
            </div>
        </div>
    </div>

@stop
@section('content')

@php $index_key=0; @endphp
@foreach ($mainLanguage as $kyLang => $Langval)
<div class="FormLanguage div_{{$Langval}} @if($index_key!=0) hidden @endif">	
	<div class="form-group">
    {!! Form::open(['method' => 'post','route' => ['admin.clubteams.updateAllPlayers'],'data-parsley-validate'=>""]) !!}
    <table class="table table-bordered table-striped">
                <tbody>
            @foreach($players as $row)
                  <tr>
                    <td>{{$row->name}}</td>
                    <td class="col-sm-12">
                    @if(isset($row->newVal[$Langval]))
                    {!! Form::text($row->id.'[]', $row->newVal[$Langval], array('class' => 'form-control')) !!}

                    @else
                    {!! Form::text($row->id.'[]', $row->name, array('class' => 'form-control')) !!}

                    @endif
                    </td>
                  </tr>
                @endforeach
                </tbody>
                </table>

                
            </div>
            {!! Form::hidden('lang[]',$Langval) !!}
            @php $index_key +=1; @endphp
        </div>
        @endforeach	 
        <div class="card-footer">
                  <button type="submit" class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2">{{trans('app.save')}}</button>
                </div>
        {!! Form::close() !!}

@stop
@section('after_foot')
@stop


