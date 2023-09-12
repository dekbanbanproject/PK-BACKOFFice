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
use App\Models\Acc_1102050101_310;
use App\Models\Acc_1102050101_302;

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


class Ucep24Controller extends Controller
 { 
    // ***************** ucep24********************************

    public function ucep24(Request $request)
    { 
            $startdate = $request->startdate;
            $enddate = $request->enddate;
     
            $date = date('Y-m-d');
            $y = date('Y') + 543;
            $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
            $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
            $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี
            $yearnew = date('Y');
            $yearold = date('Y')-1;
            $start = (''.$yearold.'-10-01');
            $end = (''.$yearnew.'-09-30'); 

            if ($startdate == '') {
                $data = DB::connection('mysql')->select('   
                        SELECT o.an,o.hn,pt.cid,concat(pt.pname,pt.fname," ",pt.lname) ptname
                        ,i.dchdate,ii.pttype
                        ,o.icode,n.`name`,a.vstdate,o.rxdate,a.vsttime,o.rxtime,o.income,o.qty,o.unitprice,o.sum_price
                        ,hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate,"",o.rxtime))) ssz 
                        FROM hos.ipt i
                        LEFT JOIN hos.opitemrece o on i.an = o.an 
                        LEFT JOIN hos.ovst a on a.an = o.an
                        left JOIN hos.er_regist e on e.vn = i.vn
                        LEFT JOIN hos.ipt_pttype ii on ii.an = i.an
                        LEFT JOIN hos.pttype p on p.pttype = ii.pttype 
                        LEFT JOIN hos.s_drugitems n on n.icode = o.icode
                        LEFT JOIN hos.patient pt on pt.hn = a.hn
                        LEFT JOIN hos.pttype ptt on a.pttype = ptt.pttype	
                        
                        WHERE i.dchdate BETWEEN "'.$date.'" and "'.$date.'"
                        and o.an is not null
                        and o.paidst ="02"
                        and p.hipdata_code ="ucs" 
                        and e.er_emergency_type  in("1","5") 
                        group BY i.an
                        ORDER BY i.an;
                '); 
            } else {
                $data = DB::connection('mysql')->select('   
                        SELECT o.an,o.hn,pt.cid,concat(pt.pname,pt.fname," ",pt.lname) ptname
                        ,i.dchdate,ii.pttype
                        ,o.icode,n.`name`,a.vstdate,o.rxdate,a.vsttime,o.rxtime,o.income,o.qty,o.unitprice,o.sum_price
                        ,hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate,"",o.rxtime))) ssz
                        FROM hos.ipt i
                        LEFT JOIN hos.opitemrece o on i.an = o.an 
                        LEFT JOIN hos.ovst a on a.an = o.an
                        left JOIN hos.er_regist e on e.vn = i.vn
                        LEFT JOIN hos.ipt_pttype ii on ii.an = i.an
                        LEFT JOIN hos.pttype p on p.pttype = ii.pttype 
                        LEFT JOIN hos.s_drugitems n on n.icode = o.icode
                        LEFT JOIN hos.patient pt on pt.hn = a.hn
                        LEFT JOIN hos.pttype ptt on a.pttype = ptt.pttype	
                        
                        WHERE i.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                        and o.an is not null
                        and o.paidst ="02"
                        and p.hipdata_code ="ucs"
                   
                        and e.er_emergency_type  in("1","5")
                       
                        group BY i.an
                        ORDER BY i.an;
                '); 
            }
            
           
         

            return view('ucep.ucep24',[
                'startdate'        =>     $startdate,
                'enddate'          =>     $enddate, 
                'data'             =>     $data, 
            ]);
    }

    public function ucep24_an(Request $request,$an)
    { 
            $startdate = $request->startdate;
            $enddate = $request->enddate;
     
            $date = date('Y-m-d');
            $y = date('Y') + 543;
            $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
            $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
            $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี
            $yearnew = date('Y');
            $yearold = date('Y')-1;
            $start = (''.$yearold.'-10-01');
            $end = (''.$yearnew.'-09-30'); 
 
                $data = DB::connection('mysql')->select('   
                        SELECT o.an,o.hn,pt.cid,concat(pt.pname,pt.fname," ",pt.lname) ptname
                        ,i.dchdate,ii.pttype
                        ,o.icode,n.name as nameliss,a.vstdate,o.rxdate,a.vsttime,o.rxtime,o.income,o.qty,o.unitprice,o.sum_price
                        ,hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate,"",o.rxtime))) ssz
                        from hos.opitemrece o
                        LEFT JOIN hos.ipt i on i.an = o.an
                        LEFT JOIN hos.ovst a on a.an = o.an
                        left JOIN hos.er_regist e on e.vn = i.vn
                        LEFT JOIN hos.ipt_pttype ii on ii.an = i.an
                        LEFT JOIN hos.pttype p on p.pttype = ii.pttype 
                        LEFT JOIN hos.s_drugitems n on n.icode = o.icode
                        LEFT JOIN hos.patient pt on pt.hn = a.hn
                        LEFT JOIN hos.pttype ptt on a.pttype = ptt.pttype	
                        
                        WHERE i.an = "'.$an.'"  
                        and o.paidst ="02"
                        and p.hipdata_code ="ucs"
                        and DATEDIFF(o.rxdate,a.vstdate)<="1"
                        and hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate," ",o.rxtime))) <="24"
                        and e.er_emergency_type  in("1","5")
                        and n.nhso_adp_code in(SELECT code from hshooterdb.h_ucep24)
                      
                '); 

                // select i.income,i.name,sum(o.qty),
                // (select sum(sum_price) from opitemrece where an=o.an and income = o.income and paidst in('02')),
                // (select sum(sum_price) from opitemrece where an=o.an and income = o.income and paidst in('01','03')),
                // (select sum(u.sum_price) from eclaimdb80.ucep_an u where u.an= o.an and i.income = u.income)
                
                // from opitemrece o
                // left outer join nondrugitems n on n.icode = o.icode
                // left outer join income i on i.income = o.income
                // where o.an ='666666666' 
                // group by i.name
                // order by i.income
             

            return view('ucep.ucep24_an',[
                'startdate'        =>     $startdate,
                'enddate'          =>     $enddate, 
                'data'             =>     $data, 
            ]);
    }
    
    // public function account_310_dashsub(Request $request,$startdate,$enddate)
    // {
    //     $datenow = date('Y-m-d');
        
    //     $dabudget_year = DB::table('budget_year')->where('active','=',true)->first();
    //     $leave_month_year = DB::table('leave_month')->orderBy('MONTH_ID', 'ASC')->get();
    //     $date = date('Y-m-d'); 
    //     // dd($end );
       
    //         $datashow = DB::select('
    //                 SELECT month(a.dchdate) as months,year(a.dchdate) as year,l.MONTH_NAME,l.MONTH_ID
    //                 ,count(distinct a.hn) as hn
    //                 ,count(distinct a.vn) as vn
    //                 ,count(distinct a.an) as an
    //                 ,sum(a.income) as income
    //                 ,sum(a.paid_money) as paid_money
    //                 ,sum(a.income)-sum(a.discount_money)-sum(a.rcpt_money) as total
    //                 ,sum(a.debit) as debit
    //                 FROM acc_debtor a
    //                 left outer join leave_month l on l.MONTH_ID = month(a.dchdate)
    //                 WHERE a.dchdate between "'.$startdate.'" and "'.$enddate.'"
    //                 and account_code="1102050101.310"
    //                 group by month(a.dchdate) order by month(a.dchdate) desc;
    //         ');
            

    //     return view('account_310.account_310_dashsub',[
    //         'startdate'          =>  $startdate,
    //         'enddate'            =>  $enddate,
    //         'datashow'           =>  $datashow,
    //         'leave_month_year'   =>  $leave_month_year,
    //     ]);
    // }
 
   
 }