<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\SearchFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use App\Mail\BaimsEmail;
use App\Mail\message;
use App\User;
use App\Model\RoleUser;
use App\Model\SocialProvider;
use App\Model\Order;
use App\Model\Post;
use App\Model\Bundle;
use App\Model\BundlePost;
use App\Model\File;
use App\Model\Page;
use App\Model\PageContent;
use App\Model\Device;
use App\Model\Contact;
use App\Model\UserNotif;
use App\Model\Video;
use App\Model\cpanelCourse;
use App\Model\Comment;
use App\Model\Promocode;
use App\Model\Category;
use App\Model\Section;
use App\Model\PromocodeMeta;
use App\Model\PasswordReset;
use App\Model\DetailsCharge;
use App\Model\Invite;
use App\Model\Feature;
use App\Model\Blog;
use App\Model\Options;
use App\Model\Tag;
use App\Model\Permission;
use App\Model\Taggable;
use App\Model\PercentVideo;
use App\Model\StudyStage;
use App\Model\AllSetting;
use App\Model\PostSocial;
use App\Model\LiveSessionChapter;
use App\Model\HistoryAffiliate;

use DB;
use Mail;
use App\Http\Controllers\ClassSiteApi\Class_UserController;
use App\Http\Controllers\ClassSiteApi\Class_CourseController;
use App\Http\Controllers\ClassSiteApi\Class_PageController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

//use Vimeo;
//require 'App_load/vimeo/autoload.php';
use App\Services\PostService;
use App\Services\VimeoService;
//use Vimeo\Laravel\Facades\Vimeo;
//use URL;
//use Hash
class ScriptController extends SiteController
{
    public function __construct()
    {
        $data_site = Options::Site_Option();
        $this->site_open = $data_site['site_open'];
        $this->lang = $data_site['lang']; //lang
        $this->def_lang= $data_site['def_lang'];
        $this->site_title = $data_site['site_title'];
        $this->site_url = $data_site['site_url'];
        //Auth::user()->id
    }

   
    public function dbase(Request $request)
    {
        $folder_id=2757096;
        //no_bill_paid_course //.no_bill_discount_course   //app.no_bill_free_course
 //script_billInstructor
        //https://baims.com/script?email=all
        //https://baims.com/script?email=sheinashmi@gmail.com
        //https://baims.com/script?email=asmaalzahrany2@gmail.com
        //https://baims.com/script?email=khalid@itech.com.kw
        //https://baims.com/script?email=sultanalsafeer@baims.com
        //https://baims.com/script?email=stat.help.kw@gmail.com
        //https://baims.com/script?email=re7abdossary@hotmail.com
        //https://ksa.baims.com/script?email=Ph.meaad@gmail.com
        //https://ksa.baims.com/script?email=Aljoharh98@outlook.sa

        // https://baims.com/script?email=all&frist_year=2016&year=2017
        // https://baims.com/script?email=all&frist_year=2018&year=2018
        // https://baims.com/script?email=all&frist_year=2019&year=2019
        // https://baims.com/script?email=all&frist_year=2020&year=2020
        // https://baims.com/script?email=all&frist_year=2021&year=2021

        //script?user=1
        $more =$last_date= '';
        if (isset($_REQUEST['user'])) {
            $frist_date = "2020-01-01 00:00:01";
            $last_date ="2020-12-31 23:59:59";

            $users =DB::table('users')->select('id')->where('is_active',1)->whereBetween(DB::raw('created_at'), [$frist_date, $last_date])->get();
           
            print_r('Count Users : ');
            print_r(count($users));
            print_r('<pre>');

            $users_orders =DB::table('users')->distinct()->select('id')->whereIn('id',function($query) use($frist_date, $last_date){
                $query->select('user_id')->where('is_active',1)->where('price','>',0)->from('orders');
            })->whereBetween(DB::raw('created_at'), [$frist_date, $last_date])->get();
            
            print_r('Count Paying users : ');
            print_r(count($users_orders));

            die;
        }
        if (isset($_REQUEST['role'])) {
            $users =User::select('*')->whereNotIn('id',function($query) {
                $query->select('user_id')->from('role_user');
            })->get();
            foreach ($users as $key => $val_user) {
                $val_user->attachRole(2);
            }
            print_r(count($users));die;
        }
        if (isset($_REQUEST['video'])) {
              // $item=Post::where('id',1)->first();
            $response = (new VimeoService)->getFolderVideos($folder_id);
            // $response=(new PostService(new VimeoService))->updatePostFromVimeo($item,$folder_id);
            print_r($response);die;
        }
     //start report by instructor email or all orders  
        if (isset($_REQUEST['more'])) {
            $more = $_REQUEST['more'];
        }
        $get_email = '';
        if (isset($_REQUEST['email'])) {
            $get_email = $_REQUEST['email'];
        }
        $def_lang=$this->def_lang;
        if (!empty($get_email)) {
            $frist_year=2016;
            $get_user = new Class_UserController();
            $name_file = '';
            if($get_email=='all'){
                $bill_order = 'pay'; //main
                $user_id = -1;
                $name_file = 'All';
                $year = 2020;
                if (isset($_REQUEST['year'])) {
                    $year = $_REQUEST['year'];
                }  
                if (isset($_REQUEST['frist_year'])) {
                    $frist_year = $_REQUEST['frist_year'];
                }
            }else{
                $user = User::where('email', $get_email)->first();
                $bill_order = 'main';
                $user_id = 0;
                if (isset($user->id)) {
                    $user_id = $user->id;
                    $name_file = $user->email;
                }
                $year = date("Y") + 1;
            }
            $frist_date = $frist_year."-01-01 00:00:01";
            if (isset($_REQUEST['frist_date'])) {
                $frist_date = $_REQUEST['frist_date'];
            }  
            $last_date = $year . "-12-30 23:59:59";
            if (isset($_REQUEST['last_date'])) {
                $last_date = $_REQUEST['last_date'];
            }  
            $orders = Order::InstructorCourseOrder($user_id, -1, 1, 0, -1, $bill_order, $frist_date, $last_date); //InstructorCourseOrder
            $allRepeart = array();
            $total_price = 0;
            foreach ($orders as $key_year => $val_ord) {
                $data = array();
                $data['Student_ID'] = $val_ord->user['id'];
                if($get_email=='all'){
                    $data['Student_Email'] = $val_ord->user['email'];
                    $data['Student_Name'] = $val_ord->user['display_name'];  //name
                    $data['Student_Phone'] = $val_ord->user['mobile'];  //phone
                }
                $data['Subject'] = '';
                if (isset($val_ord->posts->bundle_post[0]->bundles['name'])) {
                    $data['Subject'] = $val_ord->posts->bundle_post[0]->bundles['name']; //$val_ord->bundles['name']
                }
                $data['Chapter'] = $val_ord->name;
                $data['Date'] = $val_ord->created_at->format('Y-m-d');
                if ($more > 0) {
                    $data['add_by'] = $val_ord->add_by;
                    $data['bundle_id'] = $val_ord->bundle_id;
                    $data['type'] = $val_ord->type;
                    $data['sourceShare'] = $val_ord->source_share;
                    $data['transactionId'] = $val_ord->transactionId;
                    $data['paymentId'] = $val_ord->paymentId;
                    $data['numRepeat'] = $val_ord->num_repeat;
                }
                // if ($val_ord->num_repeat > 0) {
                //     $data['Price'] = '0';
                // }
                if ($val_ord->price == 0) {
                    $data['Price'] = '0';
                } else {
                    $data['Price'] = $val_ord->price; // . 'KD';
                }
                $allRepeart[] = $data;
            }
            //for dispaly in excel
            $title_file="courseBill_" . $name_file ;
            $filename = $title_file."_BaimsReport_" . date('Ymd');
            $filename_xlsx =$filename.".xlsx";
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=utf-8; encoding=UTF-8');
            header("Cache-Control: cache, must-revalidate");
            header("Pragma: public");
            // header("Content-Type: application/vnd.ms-excel");
            header("Content-Type: text/plain");
            header("Content-Disposition: attachment; filename=\"$filename_xlsx\"");
            Excel::create($filename, function ($excel) use ($title_file, $allRepeart) {
                // Set the title
                $excel->setTitle($title_file);
                // Chain the setters
                $excel->setCreator('Baims')->setCompany('Baims.com');
                $excel->setDescription('Baims');
                $data = $allRepeart;
                $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                    $sheet->setOrientation('landscape');
                    $sheet->fromArray($data, null, 'A3');
                });
            })->download('xlsx');
            exit;
        } else {
            print_r('Not found email');
            die;
        }
    }

    public function script(Request $request){ //script_subscripeachmonth
        print_r($orders);die;

        if (isset($_REQUEST['pay'])) {



            $frist_date = "2016-01-01 00:00:01";
            if (isset($_REQUEST['frist_date'])) {
                $frist_date = $_REQUEST['frist_date'];
            }  
            $last_date ="2020-12-30 23:59:59";
            if (isset($_REQUEST['last_date'])) {
                $last_date = $_REQUEST['last_date'];
            }
        // https://baims.com/dbase?pay=all&frist_date=2016-01-01 00:00:01&last_date=2020-12-30 23:59:59

            $orders= Order::leftJoin('role_user', 'role_user.user_id', '=', 'orders.user_id')
            ->select(DB::raw('user_id,count(orders.id) as count_user_pay'))
            ->whereNotIn('orders.type', ['apple','apple_test'])
            ->whereIn('role_user.role_id', [2,4])
            ->where('orders.is_active',1)->where('orders.price', '>', 0.00)
            ->whereBetween(DB::raw('orders.created_at'), [$frist_date, $last_date])
            ->groupBy('user_id')->orderBy('count_user_pay','DESC')->get();
print_r($orders);die;
            $allRepeart = [];
            $total_price = 0;
            foreach ($orders as $key_year => $val_ord) {
                $data = [];
                $data['Student_ID'] = $val_ord->user['id'];
                $data['Student_Email'] = $val_ord->user['email'];
                $data['Student_Name'] = $val_ord->user['display_name'];  //name
                $data['Student_Phone'] = $val_ord->user['mobile'];  //phone
            
                // if ($val_ord->price == 0) {
                //     $data['Price'] = '0';
                // } else {
                //     $data['Price'] = $val_ord->price; // . 'KD';
                // }
                $allRepeart[] = $data;
            }
            //for dispaly in excel
            $name_file='All'
            $title_file="courseBill_" . $name_file ;
            $filename = $title_file."_BaimsReport_" . date('Ymd');
            $filename_xlsx =$filename.".xlsx";
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=utf-8; encoding=UTF-8');
            header("Cache-Control: cache, must-revalidate");
            header("Pragma: public");
            // header("Content-Type: application/vnd.ms-excel");
            header("Content-Type: text/plain");
            header("Content-Disposition: attachment; filename=\"$filename_xlsx\"");
            Excel::create($filename, function ($excel) use ($title_file, $allRepeart) {
                // Set the title
                $excel->setTitle($title_file);
                // Chain the setters
                $excel->setCreator('Baims')->setCompany('Baims.com');
                $excel->setDescription('Baims');
                $data = $allRepeart;
                $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                    $sheet->setOrientation('landscape');
                    $sheet->fromArray($data, null, 'A3');
                });
            })->download('xlsx');
            exit;

        }elseif (isset($_REQUEST['each'])) {
        // http://127.0.0.1:8000/script?email=all
                $array_year = ['2016', '2017', '2018', '2019', '2020']; //, '2021'
                $array_month_ar = ['يناير' => '01', 'فبراير' => '02', 'مارس' => '03', 'ابريل' => '04', 'مايو' => '05', 'يونيه' => '06',
                    'يوليو' => '07', 'أغسطس' => '08', 'سبتمبر' => '09', 'أكتوبر' => '10', 'نوفمبر' => '11', 'ديسمبر' => '12'];
                $array_month = ['Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05',
                    'Jun' => '06', 'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10',
                    'Nov' => '11', 'Dec' => '12'];

                $allRepeart = [];
                $total_count = 0;
                foreach ($array_year as $key_year => $value_year) {
                    foreach ($array_month as $key_month => $value_month) {
                        $data = [];
                        $data_month = $value_year . '-' . $value_month;
                        $data['month'] = $key_month . '/' . $value_year;
                        $start_date = $data_month . '-01 00:00:01';
                        $end_date = $data_month . '-31 23:59:59';
                        $data['numberOrder'] = Order::ReportMonth($start_date, $end_date, 1);
                        $allRepeart[] = $data;
                    }
                }
                //for dispaly in excel
                $title_file="subscripes_eachMonth";
                $filename = $title_file."_" . date('Ymd');
                $filename_xlsx =$filename.".xlsx";
                header('Content-Encoding: UTF-8');
                header('Content-Type: text/csv; charset=utf-8; encoding=UTF-8');
                header("Cache-Control: cache, must-revalidate");
                header("Pragma: public");
                // header("Content-Type: application/vnd.ms-excel");
                header("Content-Type: text/plain");
                header("Content-Disposition: attachment; filename=\"$filename_xlsx\"");

                Excel::create($filename, function ($excel) use ($title_file, $allRepeart) {
                    // Set the title
                    $excel->setTitle($title_file);
                    // Chain the setters
                    $excel->setCreator('Baims')->setCompany('Baims.com');
                    $excel->setDescription('Baims');
                    $data = $allRepeart;
                    $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                        $sheet->setOrientation('landscape');
                        $sheet->fromArray($data, null, 'A3');
                    });
                })->download('xlsx');
                    exit;
                print_r('allRepeart:'.count($allRepeart));
            }
        die;
    }

    public function demo(Request $request)
    {
        // https://jsathome.baims.com/demo?limit=2405&count=200
        $limit = $count = 0;
        if (isset($_REQUEST['limit'])) {
            $limit = $_REQUEST['limit'];
        }
        if (isset($_REQUEST['count'])) {
            $count = $_REQUEST['count'];
        }
        $final_limit=$count+$limit;
        $start_limit=1+$limit;
        $allRepeart=[];
        $num_addUser =0;
        $user_role =2;
        if ($final_limit>0) {
            for ($i=$start_limit; $i <=$final_limit; $i++) {
                $user_name ='jsathome'.$i;
                $password=$user_name;
                $email=$user_name.'@baims.com';
                $found_user=User::where('email', $email)->first();
                if (!isset($found_user->id)) {
                    $code_country = '+965';
                    $jop = null;
                    $display_name =$user_name;
                    $phone = rand(0, 99999) . rand(0, 99999);
                    $server = key_server_country();
                    $lang = 'ar';//User::AddcurrentLang();
                    $num_addUser += 1;
                    $user = User::updateOrCreate(['email' => $email], [
                        'display_name' => $display_name,
                        'email' =>$email,
                        'password' => bcrypt($password),
                        'name' => (str_replace(' ', '_', $user_name . time())), //str_random(8)
                        'phone' => $phone,
                        'mobile' => null,
                        'code_country' => $code_country,
                        'image' => generateDefaultImage($display_name),
                        'access_token' => '',
                        'fcm_token' => '',
                        'is_agreed' => 1,
                        'is_active' => 1,
                        'reg_site' => 'site',
                        'education' => '',
                        'address' => '',
                        'country' => 'KW',
                        'jop' => $jop,
                        'server' => $server,
                        'mobile_verif' => 1,
                        'lang' => $lang
                    ]);
                    $user->attachRole($user_role);
                    $user->syncTenants([6]);
                    $excel_array['email']=$email;
                    $excel_array['password']=$password;
                    $allRepeart[]=$excel_array;
                } else {
                    $excel_array['email']=$found_user->email;
                    $excel_array['password']=$password;
                    $found_user->update(['password' => bcrypt($password)]);
                    $allRepeart[]=$excel_array;
                }
            }
        }
        if (count($allRepeart) > 0) {
            //for dispaly in excel
            $title_file="users_jsathome_".date('Ymd');
            $filename = $title_file;
            $filename_xlsx =$filename.".xlsx";
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=utf-8; encoding=UTF-8');
            header("Cache-Control: cache, must-revalidate");
            header("Pragma: public");
            // header("Content-Type: application/vnd.ms-excel");
            header("Content-Type: text/plain");
            header("Content-Disposition: attachment; filename=\"$filename_xlsx\"");
            Excel::create($filename, function ($excel) use ($title_file, $allRepeart) {
                // Set the title
                $excel->setTitle($title_file);
                // Chain the setters
                $excel->setCreator('Baims')->setCompany('Baims.com');
                $excel->setDescription('Baims');
                $data = $allRepeart;
                $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                    $sheet->setOrientation('landscape');
                    $sheet->fromArray($data, null, 'A3');
                });
            })->download('xlsx');
            exit;
        }
        print_r('allRepeart:'.count($allRepeart));
        die;
    }
    public function vidscript(Request $request)
    {
        if (isset($_REQUEST['update'])) {
            // https://ksa.baims.com/vidscript?update=1
            // $data=Order::whereIn('id',[628811,628814,628867,628864,628865,628866])->get();
            // print_r($data);die;
            // $data=Post::where('id',)->update(['previewed_at'=>null]);
            // print_r($data);die;
        }
        //https://jsathome.baims.com/vidscript?limit=11  //12
        $limit =0;
        if (isset($_REQUEST['limit'])) {
            $limit = $_REQUEST['limit'];
        }
        $count_array=13;
        $array_users=[
                    0=>[0=>1,1=>247],
                    1=>[0=>248,1=>497],
                    2=>[0=>489,1=>744],
                    3=>[0=>745,1=>997],
                    4=>[0=>1001,1=>1246],
                    5=>[0=>1247,1=>1497],
                    6=>[0=>1498,1=>1747],
                    7=>[0=>1748,1=>1997],
                    8=>[0=>2001,1=>2005],
                    9=>[0=>2006,1=>2055],
                    10=>[0=>2056,1=>2205],
                    11=>[0=>2206,1=>2305],
                    12=>[0=>2306,1=>2405],
                ];
        $array_courses=[
                    0=>[36806,36807,36808,36809],
                    1=>[36814,36815,36816,36817],
                    2=>[37109,37110,37111,37113],
                    3=>[37118,37119,37120,37121],
                    4=>[36810,36811,36812,36813],
                    5=>[36818,36819,36820,36821],
                    6=>[37114,37115,37116,37117],
                    7=>[37122,37123,37124,37125],
                    8=>[36810,36811,36812,36813],
                    9=>[36818,36819,36820,36821],
                    10=>[37114,37115,37116,37117],
                    11=>[48234,48235,48236,48237],
                    12=>[48238,48239,48240,48241],
                ];
        $num_count=0;
        $start_limit=0;
        $final_limit=0;
        if ($limit < $count_array) {
            $posts=Post::whereIn('id', $array_courses[$limit])->get();
            $start_limit=$array_users[$limit][0];
            $final_limit=$array_users[$limit][1];
            for ($i=$start_limit; $i <=$final_limit; $i++) {
                $email ='jsathome'.$i.'@baims.com';
                $found_user=User::where('email', $email)->first();
                if (isset($found_user->id)) {
                    $num_count +=1;
                    $this->addsubscribe($found_user->id, $posts);
                }
            }
        }
        print_r('Done num_count :'.$num_count);
        print_r('start_limit :'.$start_limit);
        print_r('final_limit :'.$final_limit);
        die;
    }
    
    public function addsubscribe($user_id, $posts)
    {
        foreach ($posts as $key_post => $post) {
            $input['user_id']=$user_id;
            $input['post_id'] = $post->id;
            $input['post_id'] = $post->id;
            $insertOrder = $update_insert = 0;
            $price_course = $post->price;
            $discount_course = $post->discount;
            $course_title = $post->name;
//            $date = date("Y-m-d");
            $input['link'] = get_RandLink($course_title);
            $source_share = 'site_free';
            $total_discount = $discount = 100;
            $price = 0;
            //check found course for user_id
            $dataFoundOrder = Order::CheckFoundOrderACtiveShare($input['user_id'], $input['post_id'], 1, 1);
            if (!isset($dataFoundOrder->id)) {
                $sub_week_date=Carbon::now()->subWeek()->toDateString();
                $dataFoundOrder = Order::get_LastRowShareDate($input['user_id'], $input['post_id'], 'id', $sub_week_date);
            }
            $new_add = 0;
            if (isset($dataFoundOrder->id)) {
                $order_id = $dataFoundOrder->id;

                if ($dataFoundOrder->is_active == 1 && $dataFoundOrder->is_share == 1 && $dataFoundOrder->type_request == 'accept') {
                    $ok_found_befor=1;
                } elseif ($dataFoundOrder->is_active == 0 && $dataFoundOrder->is_share == 1 && $dataFoundOrder->type_request != 'accept') {
                    $update_insert = Order::updateOrderBuy($dataFoundOrder->id, $input['user_id'], $price, $total_discount, 1, 'accept', 'baims', $source_share);
                } elseif ($dataFoundOrder->is_active == 1 && $dataFoundOrder->is_share == 0 && $dataFoundOrder->type_request == 'accept') {
                    $update_insert = Order::updateOrderShareRepeat($dataFoundOrder->id, 1, 1, 1);
                } else {
                    $new_add = 1;
                }
            } else {
                $new_add = 1;
            }
            if ($new_add == 1) {
                //no user_id share in course
                $insertOrder = Order::insertOrder($input['user_id'], $post, 1, 'baims', $source_share, 'accept', $total_discount, 1, $price);
            }
        }
        return true;
    }
}
