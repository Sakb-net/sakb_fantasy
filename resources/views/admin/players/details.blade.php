@extends('admin.layouts.app')
@section('title') {{trans('app.details')}} 
@stop
@section('head_content')
@stop
@section('content')


            <table class="table table-striped">
                <tbody>
                  <tr>
                    <td>{{trans('app.name')}} : </td>
                    <td>{{$details->name}}</td>
                  </tr>
                  <tr>
                    <td>{{trans('app.team')}} : </td>
                    <td>{{$details->location_player->value_ar}}</td>
                  </tr>

                   <tr>
                    <td>{{trans('app.tShirt')}} : </td>
                    <td>{{$details->num_t_shirt}}</td>
                  </tr>
                  <tr>
                    <td>{{trans('app.nationality')}} : </td>
                    <td>{{$details->nationality}}</td>
                  </tr>
                  <tr>
                    <td>{{trans('app.weight')}} : </td>
                    <td>{{$details->weight}}</td>
                  </tr>

                   <tr>
                    <td>{{trans('app.height')}} : </td>
                    <td>{{$details->height}}</td>
                  </tr>
                  <tr>
                    <td>{{trans('app.foot')}} : </td>
                    <td>{{$details->foot}}</td>
                  </tr>
                  <tr>
                    <td>{{trans('app.cost')}} : </td>
                    <td>{{$details->cost}}</td>
                  </tr>

                   <tr>
                    <td>{{trans('app.position')}} : </td>
                    <td>{{$details->teams->name}}</td>
                  </tr>
                  <tr>
                    <td>{{trans('app.state')}} : </td>
                    <td>
                    @if ($details->is_active == 1)
                    {{trans('app.active')}} 
                    @else
                    {{trans('app.not')}}
                    @endif
                    </td>
                  </tr>

               </tbody>
              </table>  



@stop
@section('after_foot')
@stop