<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\Acc_debtor;
use App\Models\Pttype_eclaim;
use App\Models\Account_listpercen;
use App\Models\Leave_month;
use App\Models\Acc_debtor_stamp;
use App\Models\Acc_debtor_sendmoney;
use App\Models\Pttype;
use App\Models\Pttype_acc; 
use App\Models\Department;
use App\Models\Departmentsub;
use App\Models\Departmentsubsub;
use App\Models\Position;
use App\Models\Product_spyprice;
use App\Models\Products;
use App\Models\Products_type;
use App\Models\Product_group;
use App\Models\Product_unit;
use App\Models\Products_category;
use App\Models\Article;
use App\Models\Product_prop;
use App\Models\Product_decline;
use App\Models\Department_sub_sub;
use App\Models\Products_vendor;
use App\Models\Status;
use App\Models\Products_request;
use App\Models\Products_request_sub;
use App\Models\Acc_stm_prb;
use App\Models\Acc_stm_ti_totalhead;
use App\Models\Acc_stm_ti_excel;
use App\Models\Acc_stm_ofc;
use App\Models\acc_stm_ofcexcel;
use App\Models\Acc_stm_lgo;
use App\Models\Acc_stm_lgoexcel;
use App\Models\Check_sit_auto;
use App\Models\Acc_stm_ucs_excel;
use App\Models\Fire_check;
use App\Models\Fire;
use App\Models\Cctv_report_months;
use App\Models\Product_budget;
use App\Models\Product_method;
use App\Models\Product_buy;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use App\Mail\DissendeMail;
use Mail;
use Illuminate\Support\Facades\Storage;
use Auth;
use Http;
use SoapClient;
// use File;
// use SplFileObject;
use Arr;
// use Storage;
use GuzzleHttp\Client;

use App\Imports\ImportAcc_stm_ti;
use App\Imports\ImportAcc_stm_tiexcel_import;
use App\Imports\ImportAcc_stm_ofcexcel_import;
use App\Imports\ImportAcc_stm_lgoexcel_import;
use App\Models\Acc_1102050101_217_stam;
use App\Models\Acc_opitemrece_stm;

use SplFileObject;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

date_default_timezone_set("Asia/Bangkok");


class SupportPRSController extends Controller
 { 
    public function support_system_dashboard(Request $request)
    {
        $datenow = date('Y-m-d');
        $months = date('m');
        $year = date('Y'); 
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $count_red_all        = Fire::where('active','Y')->where('fire_color','red')->count(); 
        $count_green_all      = Fire::where('active','Y')->where('fire_color','green')->count();

        $data['count_red_back']        = Fire::where('active','Y')->where('fire_color','red')->where('fire_backup','Y')->count(); 
        $data['count_green_back']      = Fire::where('active','Y')->where('fire_color','green')->where('fire_backup','Y')->count();
       
            $chart_red = DB::connection('mysql')->select(' 
                    SELECT * FROM
                    (SELECT COUNT(fire_num) as count_red FROM fire_check WHERE fire_check_color ="red" AND YEAR(check_date)= "'.$year.'") reds
                    ,(SELECT COUNT(fire_num) as count_greens FROM fire_check WHERE fire_check_color ="green" AND YEAR(check_date)= "'.$year.'") green
            '); 
            foreach ($chart_red as $key => $value) {                
                if ($value->count_red > 0) {
                    // $dataset_s[] = [ 
                        // $count_color_qty         = $value->count_color;
                        // $count_color_percent     = 100 / $count_red * $value->count_color;
                        $count_red_percent          = 100 / $count_red_all * $value->count_red; 
                        $count_color_red_qty        = $value->count_red;
                        $count_red_alls             = $count_red_all;

                        $count_green_percent        = 100 / $count_green_all * $value->count_greens; 
                        $count_color_green_qty      = $value->count_greens;
                        $count_green_alls           = $count_green_all;
                    // ];
                }
            }

            // $Dataset_show = $dataset_s;
            // dd($count_color_qty);

        return view('support_prs.support_system_dashboard',$data,[
            'startdate'               =>  $startdate,
            'enddate'                 =>  $enddate, 
            'count_color_red_qty'     =>  $count_color_red_qty,
            'count_red_all'           =>  $count_red_all,
            'count_green_all'         =>  $count_green_all,
            'count_red_percent'       =>  $count_red_percent,

            'count_green_percent'     =>  $count_green_percent,
            'count_color_green_qty'   =>  $count_color_green_qty,
        ]);
    }
    public function support_dashboard_chart(Request $request)
    {
        $datenow = date('Y-m-d');
        $months = date('m');
        $year = date('Y'); 
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $count_red        = Fire::where('active','Y')->where('fire_color','red')->count(); 
        $count_green      = Fire::where('active','Y')->where('fire_color','green')->count();

        $data['count_red_back']        = Fire::where('active','Y')->where('fire_color','red')->where('fire_backup','Y')->count(); 
        $data['count_green_back']      = Fire::where('active','Y')->where('fire_color','green')->where('fire_backup','Y')->count();
       
            $chart_red = DB::connection('mysql')->select(' 
                    SELECT * FROM
                    (SELECT COUNT(fire_num) as count_red FROM fire_check WHERE fire_check_color ="red" AND YEAR(check_date)= "'.$year.'") reds
                    ,(SELECT COUNT(fire_num) as count_greens FROM fire_check WHERE fire_check_color ="green" AND YEAR(check_date)= "'.$year.'") green
            '); 
            foreach ($chart_red as $key => $value) {                
                if ($value->count_red > 0) {
                    $dataset2[] = [ 
                        'count_red'                  => 100 / $count_red * $value->count_red, 
                        'count_color_red_qty'        => $value->count_red,
                        'count_red_all'              => $count_red,

                        // $count_green_percent        = 100 / $count_green_all * $value->count_greens; 
                        'count_green_percent'        => 100 / $count_green * $value->count_greens, 
                        'count_color_green_qty'      => $value->count_greens,
                        'count_green_all'            => $count_green,
                    ];
                }
            }

            $Dataset1 = $dataset2;
            // dd($Dataset1);

            return response()->json([
                'status'                    => '200', 
                'Dataset1'                  => $Dataset1, 
            ]);
    }
    public function support_system(Request $request)
    {
        $datenow = date('Y-m-d');
        $months = date('m');
        $year = date('Y'); 
        $startdate = $request->startdate;
        $enddate = $request->enddate;
         
        return view('support_prs.support_system',[
            'startdate'     =>     $startdate,
            'enddate'       =>     $enddate, 
        ]);
    }    
 
    public function cctvqrcode(Request $request, $id)
    {

            $cctvprint = Article::where('article_id', '=', $id)->first();

        return view('cctv.cctvqrcode', [
            'cctvprint'  =>  $cctvprint
        ]);

    }
     
 

 }