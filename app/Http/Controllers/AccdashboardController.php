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
use App\Models\Acc_dashboard;

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

class AccdashboardController extends Controller
{
  public function account_pk_dash(Request $request)
  {
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $date = date('Y-m-d');
        $y = date('Y') + 543; 
        $yearnew = date('Y');
        $yearold = date('Y')-1;
        $start = (''.$yearold.'-10-01');
        $end = (''.$yearnew.'-09-30');  
      //  dd($startdate);
        if ($startdate != '') {
          Acc_dashboard::truncate();
            $data =  DB::connection('mysql2')->select('              
                  SELECT 
                    month(v.vstdate) as months,year(v.vstdate) as year,pt.hipdata_code,count(distinct v.vn) as count_vn
                    ,sum(v.income) as income
                    ,sum(v.discount_money) as discount_money
                    ,sum(v.rcpt_money) as rcpt_money
                    ,sum(v.income)-sum(v.discount_money)-sum(v.rcpt_money) as debit 
                    from hos.vn_stat v 
                    LEFT JOIN hos.visit_pttype vp on vp.vn = v.vn 
                    LEFT JOIN hos.pttype pt on v.pttype=pt.pttype 
                    WHERE v.vstdate BETWEEN "'. $startdate.'" AND "'. $enddate.'"
                    AND pt.hipdata_code <> ""
                    GROUP BY month(v.vstdate),pt.hipdata_code 
                    ORDER BY v.vstdate desc
            ');
          
            foreach ($data as $key => $value) { 
              Acc_dashboard::insert([
                  'months'                 => $value->months,
                  'year'                   => $value->year,
                  'hipdata_code'           => $value->hipdata_code,
                  'count_vn'               => $value->count_vn,
                  'income'                 => $value->income,
                  'discount_money'         => $value->discount_money,
                  'rcpt_money'             => $value->rcpt_money,
                  'debit'                  => $value->debit,                
              ]);
            } 
        }
        
        $datashow = Acc_dashboard::where('months',' month("'. $startdate.'")')->get();
      
    return view('dashboard.account_pk_dash', [ 
      'datashow'          =>  $datashow,
      'startdate'         =>  $startdate,
      'enddate'           =>  $enddate,
    ]);
  }
  public function account_dashline(Request $request)
  {
    $startdate = $request->startdate;
    $enddate = $request->enddate;
    $date = date('Y-m-d');
    $y = date('Y') + 543; 
    $yearnew = date('Y');
    $yearold = date('Y')-1;
    $start = (''.$yearold.'-10-01');
    $end = (''.$yearnew.'-09-30');
      if ($startdate != '') {
            $labels = [
              1 => "ม.ค", "ก.พ", "มี.ค", "เม.ย", "พ.ย", "มิ.ย", "ก.ค","ส.ค","ก.ย","ต.ค","พ.ย","ธ.ค"
            ];
            $chart = DB::connection('mysql')->select(' 
                SELECT months,year,hipdata_code,count_vn,income,rcpt_money,debit 
                from acc_dashboard                 
                WHERE months BETWEEN month("'.$startdate.'") AND month("'.$enddate.'")  
                AND year BETWEEN year("'.$startdate.'") AND year("'.$enddate.'") 
                ORDER BY months desc
            ');
            // GROUP BY months,pt.hipdata_code
            foreach ($chart as $key => $value) {
                
                if ($value->count_vn > 0) {
                    $dataset[] = [
                        // 'label'               => $labels,
                        'label'               => $value->hipdata_code,
                        'count_vn'            => $value->count_vn,
                        'income'              => $value->income,
                        'rcpt_money'          => $value->rcpt_money,
                        'debit'               => $value->debit
                    ];
                }
            }

            $Dataset1 = $dataset;
            // $Dataset2 = $dataset_2; 
            // return response()->json([
            //     'status'                    => '200', 
            //     'Dataset1'                  => $Dataset1,
            //     // 'Dataset2'                  => $Dataset2
            // ]);
      } else {
            $labels = [
              1 => "ม.ค", "ก.พ", "มี.ค", "เม.ย", "พ.ย", "มิ.ย", "ก.ค","ส.ค","ก.ย","ต.ค","พ.ย","ธ.ค"
            ];
            $chart = DB::connection('mysql')->select(' 
                SELECT months,year,hipdata_code,count_vn,income,rcpt_money,debit 
                from acc_dashboard                 
                GROUP BY year,hipdata_code
                ORDER BY months desc
            ');
            // WHERE months BETWEEN month("'.$start.'") AND month("'.$end.'")  
            // AND year BETWEEN year("'.$start.'") AND year("'.$end.'") 
            // dd($chart);
            foreach ($chart as $key => $value) {
                
                if ($value->count_vn > 0) {
                    $dataset2[] = [
                        'label'              => $value->hipdata_code,
                        'count_vn'           => $value->count_vn,
                        'income'             => $value->income,
                        'rcpt_money'         => $value->rcpt_money,
                        'debit'              => $value->debit
                    ];
                }
            }

            $Dataset1 = $dataset2;
            // $Dataset2 = $dataset_2; 
            return response()->json([
              'status'                    => '200', 
              'Dataset1'                  => $Dataset1,
              // 'Dataset2'                  => $Dataset2
          ]);
      }
      
      
      
  }
}
