@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            <div class="filter row">
            <div class="all-fix">
                <div class="col-md-8 col-sm-6"><p id="teamName">{{trans('app.allMatches')}}</p></div>
                <div class="col-md-4 col-sm-6">
                    <!--select club-->
                    <select class="form-control" id="selectTeamChange">
                        <option value = ''>{{trans('app.allMatches')}}</option>
                        @foreach($allTeams as $value)
                        <option value ="{{$value->link}}">{{$value->name}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
                <div class="myfixtures LastNewMatch_eldawry" id="LastNewMatch_eldawry">
                    
                </div>
                <div id="infoModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close close_player" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{{trans('app.player_data')}}</h4>
                            </div>
                            <div class="modal-body pt0 p-data">
                                <div class="row draw_player_InfoModal">
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="styled-pagination col-md-12">
                    <ul class="pagination pagination_fixtures draw_pagination_fixtures">
                       
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<a data-toggle="modal" data-target="#infoModal" data-dismiss="modal" class="butn butn-bg w100 btnMod_info_player hidden"></a>
@include('site.games.modal.info_modal')
@include('site.home.sponsers')
@endsection
@section('after_head')
@stop  
@section('after_foot')
@include('site.layouts.script.game_js')
@include('site.layouts.script.match_js')
<script>
$(document).ready(function () {    
    var url_string =window.location.href;
    var data_url = new URL(url_string);
    var num_page =data_url.searchParams.get("week");

    GetDataMatch_Public(pub_limit_match,1,'fixtures',num_page,'');


    $("#selectTeamChange").on('change', function(){
        var teamName = $('#selectTeamChange option:selected').text()
        $('#teamName').text(teamName);
        GetDataMatch_Public(pub_limit_match,1,'fixtures',num_page,$('#selectTeamChange').val());
    })

});
</script>
@stop  