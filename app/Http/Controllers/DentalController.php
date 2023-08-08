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
use App\Models\Acc_stm_ti;
use App\Models\Acc_stm_ti_total;
use App\Models\Acc_opitemrece;
use App\Models\Acc_1102050101_202;
use App\Models\Acc_1102050101_217;
use App\Models\Acc_1102050101_2166;
use App\Models\Acc_stm_ucs;
use App\Models\Acc_1102050101_301;
use App\Models\Acc_1102050101_304;
use App\Models\Acc_1102050101_308;
use App\Models\Acc_1102050101_4011;
use App\Models\Acc_1102050101_3099;
use App\Models\Acc_1102050101_401;
use App\Models\Acc_1102050101_402;
use App\Models\Acc_1102050102_801;
use App\Models\Acc_1102050102_802;
use App\Models\Acc_1102050102_803;
use App\Models\Acc_1102050102_804;
use App\Models\Acc_1102050101_4022;
use App\Models\Acc_1102050102_602;
use App\Models\Acc_1102050102_603;
use App\Models\Acc_stm_prb;
use App\Models\Acc_stm_ti_totalhead;
use App\Models\Acc_stm_ti_excel;
use App\Models\Acc_stm_ofc;
use App\Models\acc_stm_ofcexcel;
use App\Models\Acc_stm_lgo;
use App\Models\Acc_stm_lgoexcel;
use App\Models\Check_sit_auto;
use App\Models\Acc_stm_ucs_excel;
use App\Models\Car_service;
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


class DentalController extends Controller
 {
    // ***************** 301********************************
     
    public function dental (Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $dabudget_year = DB::table('budget_year')->where('active','=',true)->first();
        $leave_month_year = DB::table('leave_month')->orderBy('MONTH_ID', 'ASC')->get();
        $date = date('Y-m-d');
        $y = date('Y') + 543;
        $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
        $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
        $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี

        
        $datashow = DB::select('
            SELECT month(a.vstdate) as months,year(a.vstdate) as year,l.MONTH_NAME
                ,count(distinct a.hn) as hn
                ,count(distinct a.vn) as vn
                ,sum(a.paid_money) as paid_money
                ,sum(a.income) as income
                ,sum(a.income)-sum(a.discount_money)-sum(a.rcpt_money) as total
                FROM acc_debtor a
                left outer join leave_month l on l.MONTH_ID = month(a.vstdate)
                WHERE a.vstdate between "'.$newyear.'" and "'.$date.'"
                and account_code="1102050101.301"
                and income <> 0
                group by month(a.vstdate) order by month(a.vstdate) desc limit 3;
        ');

        

        $data_doctor = DB::connection('mysql3')->select('
            SELECT code,CONCAT(pname,fname," ",lname) dentname
            FROM doctor 
            WHERE position_id = "2"
            AND active = "Y"
        ');

        $event = array();       
        $carservicess = Car_service::all();  
        foreach ($carservicess as $carservice) {
       
            if ($carservice->car_service_status == 'request') {
                $color = '#F48506';
            }elseif ($carservice->car_service_status == 'allocate') {
                $color = '#592DF7'; 
            }elseif ($carservice->car_service_status == 'allocateall') {
                $color = '#07D79E';   
            }elseif ($carservice->car_service_status == 'cancel') {
                $color = '#ff0606';  
            }elseif ($carservice->car_service_status == 'confirmcancel') {
                $color = '#ab9e9e';  
            }elseif ($carservice->car_service_status == 'noallow') {
                $color = '#E80DEF';                   
            } else {
                $color = '#3CDF44';
            }
    
            $dateend = $carservice->car_service_date;
            // $dateend = $carservice->car_service_length_backdate;
            $NewendDate = date ("Y-m-d", strtotime("1 day", strtotime($dateend)));
    
            // $datestart=date('H:m');
            $timestart = $carservice->car_service_length_gotime;  
            $timeend = $carservice->car_service_length_backtime; 
            $starttime = substr($timestart, 0, 5);  
            $endtime = substr($timeend, 0, 5); 
    
            $showtitle = $carservice->car_service_register.'=>'.$starttime.'-'.$endtime;
            
            $event[] = [
                'id' => $carservice->car_service_id, 
                'title' => $showtitle, 
                'start' => $dateend,
                'end' => $dateend,
                'color' => $color
            ];
        } 

        return view('dent.dental',[
            'startdate'        => $startdate,
            'enddate'          => $enddate, 
            'datashow'         => $datashow, 
            'data_doctor'      => $data_doctor,
            'events'     =>  $event, 
        ]);
    }

   
 

 }