$(document).ready(function () {
    $('body').on('click', '.pop_confirm_saveteam', function () { 
        var obj = $(this);
        //click on model
        $('body').find('.btnConfirm_saveteamModal').click();
        return false;
    });

    $('body').on('click', '.confirm_save_changeteam', function () { 
        var obj = $(this);
        // var subeldwry_link = obj.attr('data-subeldwry');
        var success_msg = success_dataDiv();
        $('body').find('.notif-msg').html(success_msg);
        return false;
    }); 
    $('body').on('click', '.calculate_captain_triple', function () { //change
        var obj = $(this);
        get_dataTripleCaptainPoints();
        return false;
    });
    $('body').on('click', '.calculate_bench_players', function () { //change
        var obj = $(this);
        get_dataBenchPlayersPoints();
        return false;
    });

    $('body').on('click', '.cancelBenchCard', function () {
        var obj = $(this);
        cancelBenchTripleCard('bench_card','triple_card');
        return false;
    });
    $('body').on('click', '.cancelTripleCard', function () {
        var obj = $(this);
        cancelBenchTripleCard('triple_card','bench_card');
        return false;
    });
    //**********
});

function check_btns_status() {
    $.ajax({
        type: "post",
        url: url + '/check_btns_status',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            DisplayOrNot_benchTripleCard(data.cards_status.bench_card,data.cards_status.triple_card);
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function get_dataTripleCaptainPoints() {
    $.ajax({
        type: "post",
        url: url + '/get_dataTripleCaptainPoints',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            // val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            $('body').find('.captain_triple_card').removeClass('disabled');
            $('.captain_triple_card').prop('disabled', false);
            $('body').find('.cancelTripleCard').removeClass('disabled');
            $('.cancelTripleCard').prop('disabled', false);

            $('body').find('.bench_players_card').addClass('disabled');
            $('.bench_players_card').prop('disabled', true);
            $('body').find('.calculate_captain_triple').addClass('disabled');
            $('.calculate_captain_triple').prop('disabled', true);
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function get_dataBenchPlayersPoints() {
    $.ajax({
        type: "post",
        url: url + '/get_dataBenchPlayersPoints',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            // val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            $('body').find('.bench_players_card').removeClass('disabled');
            $('.bench_players_card').prop('disabled', false);
            $('body').find('.cancelBenchCard').removeClass('disabled');
            $('.cancelBenchCard').prop('disabled', false);

            $('body').find('.captain_triple_card').addClass('disabled');
            $('.captain_triple_card').prop('disabled', true);
            $('body').find('.calculate_bench_players').addClass('disabled');
            $('.calculate_bench_players').prop('disabled', true);
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function cancelBenchTripleCard(colum,return_colum) {
    $.ajax({
        type: "post",
        url: url + '/cancelBenchTripleCard',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            colum:colum,
            return_colum:return_colum,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            DisplayOrNot_benchTripleCard(data.cards_status.bench_card,data.cards_status.triple_card);
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function DisplayOrNot_benchTripleCard(benchCard,tripleCard){
    if(benchCard==1 &&  tripleCard!=1){
        $('body').find('.captain_triple_card').addClass('disabled');
        $('.captain_triple_card').prop('disabled', true);
        $('body').find('.calculate_bench_players').addClass('disabled');
        $('.calculate_bench_players').prop('disabled', true);
        $('body').find('.cancelBenchCard').removeClass('disabled');
        $('.cancelBenchCard').prop('disabled', false);
    }else if(benchCard !=1 && tripleCard ==1){
        $('body').find('.bench_players_card').addClass('disabled');
        $('.bench_players_card').prop('disabled', true);
        $('body').find('.calculate_captain_triple').addClass('disabled');
        $('.calculate_captain_triple').prop('disabled', true);
        $('body').find('.cancelTripleCard').removeClass('disabled');
        $('.cancelTripleCard').prop('disabled', false);
    }else{
        if(benchCard < 0){
            $('body').find('.bench_players_card').addClass('disabled');
            $('.bench_players_card').prop('disabled', true);
        }else{
            $('body').find('.bench_players_card').removeClass('disabled');
            $('.bench_players_card').prop('disabled', false);
            $('body').find('.calculate_bench_players').removeClass('disabled');
            $('.calculate_bench_players').prop('disabled', false);
            $('body').find('.cancelBenchCard').addClass('disabled');
            $('.cancelBenchCard').prop('disabled', true);
        }
        if(tripleCard < 0){
            $('body').find('.captain_triple_card').addClass('disabled');
            $('.captain_triple_card').prop('disabled', true);
        }else{
            $('body').find('.captain_triple_card').removeClass('disabled');
            $('.captain_triple_card').prop('disabled', false);
            $('body').find('.calculate_captain_triple').removeClass('disabled');
            $('.calculate_captain_triple').prop('disabled', false);
            $('body').find('.cancelTripleCard').addClass('disabled');
            $('.cancelTripleCard').prop('disabled', true);
        }
    }
    return false;
}

