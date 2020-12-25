<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eldwry;
// use App\Models\Subeldwry;
use App\Models\GameHistory;
use App\Models\AllSetting;
use App\Http\Controllers\OptaApi\Class_OptaController;

class ResultMatchCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature ='resultmatch:cron';//eman:to use this to test in terminal

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'when start match get match result fron opta each 1 min';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //script that done in cron
        //command to test in terminal: php artisan resultmatch:cron

        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            //check time and pull data of game user and save in history
            GameHistory::CheckTime_StopSubeldwry();
            //end

            //get data of curent match from opta
            $run_match=new Class_OptaController();
            $run_match->GetCurrentMatches();
            //end

            // //test cron work
            // $data_test=AllSetting::get_rowSetting('ResultMatchCron');
            // $setting_value=1;
            // if(isset($data_test->id)){
            //     $setting_value +=$data_test->setting_value;
            // }
            // AllSetting::insertUpdateSetting('ResultMatchCron', $setting_value,null,1);
            // //end test 
        }
        dd('End ResultMatchCron');
    }
}
