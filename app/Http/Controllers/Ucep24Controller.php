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
use App\Models\Acc_ucep24;

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
                 
                $data = DB::connection('mysql')->select('SELECT * from acc_ucep24 group by an');  

            } else {
                $data_ = DB::connection('mysql')->select('   
                        SELECT a.vn,o.an,o.hn,pt.cid,concat(pt.pname,pt.fname," ",pt.lname) ptname
                        ,i.dchdate,ii.pttype
                        ,o.icode,n.`name` as namelist,a.vstdate,o.rxdate,a.vsttime,o.rxtime,o.income,o.qty,o.unitprice,o.sum_price
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
                        and DATEDIFF(o.rxdate,a.vstdate)<="1"
                        and hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate," ",o.rxtime))) <="24"
                        and e.er_emergency_type  in("1","2","5")
                       
                        group BY i.an,o.icode,o.rxdate
                        ORDER BY i.an;
                '); 
                // and n.nhso_adp_code in(SELECT code from hshooterdb.h_ucep24)
                Acc_ucep24::truncate();
                foreach ($data_ as $key => $value) {    
                    Acc_ucep24::insert([
                        'vn'                => $value->vn,
                        'hn'                => $value->hn,
                        'an'                => $value->an,
                        'cid'               => $value->cid,
                        'ptname'            => $value->ptname,
                        'vstdate'           => $value->vstdate,
                        'rxdate'            => $value->rxdate,
                        'dchdate'           => $value->dchdate,
                        // 'pttype'            => $value->pttype, 
                        'income'            => $value->income, 
                        'icode'             => $value->icode,
                        'name'              => $value->namelist,
                        'qty'               => $value->qty,
                        'unitprice'         => $value->unitprice,
                        'sum_price'         => $value->sum_price, 
                    ]);
                }
                $data = DB::connection('mysql')->select('SELECT * from acc_ucep24 group by an');  
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
                       

                        select o.an,i.income,i.name as nameliss,sum(o.qty) as qty,
                        (select sum(sum_price) from hos.opitemrece where an=o.an and income = o.income and paidst in("02")) as paidst02,
                        (select sum(sum_price) from hos.opitemrece where an=o.an and income = o.income and paidst in("01","03")) as paidst0103,
                        (select sum(u.sum_price) from acc_ucep24 u where u.an= o.an and i.income = u.income) as paidst_ucep
                        from hos.opitemrece o
                        left outer join hos.nondrugitems n on n.icode = o.icode
                        left outer join hos.income i on i.income = o.income
                        where o.an = "'.$an.'"  
                        group by i.name
                        order by i.income
                      
                '); 

                // SELECT o.an,o.hn,pt.cid,concat(pt.pname,pt.fname," ",pt.lname) ptname
                // ,i.dchdate,ii.pttype
                // ,o.icode,n.name as nameliss,a.vstdate,o.rxdate,a.vsttime,o.rxtime,o.income,o.qty,o.unitprice,o.sum_price
                // ,hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate,"",o.rxtime))) ssz
                // from hos.opitemrece o
                // LEFT JOIN hos.ipt i on i.an = o.an
                // LEFT JOIN hos.ovst a on a.an = o.an
                // left JOIN hos.er_regist e on e.vn = i.vn
                // LEFT JOIN hos.ipt_pttype ii on ii.an = i.an
                // LEFT JOIN hos.pttype p on p.pttype = ii.pttype 
                // LEFT JOIN hos.s_drugitems n on n.icode = o.icode
                // LEFT JOIN hos.patient pt on pt.hn = a.hn
                // LEFT JOIN hos.pttype ptt on a.pttype = ptt.pttype	
                
                // WHERE i.an = "'.$an.'"  
                // and o.paidst ="02"
                // and p.hipdata_code ="ucs"
                // and DATEDIFF(o.rxdate,a.vstdate)<="1"
                // and hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate," ",o.rxtime))) <="24"
                // and e.er_emergency_type  in("1","5")
                // and n.nhso_adp_code in(SELECT code from hshooterdb.h_ucep24)
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
    public function ucep24_income(Request $request,$an,$income)
    { 
            $startdate = $request->startdate;
            $enddate = $request->enddate;
            // select *
            // from acc_ucep24                         
            // where an = "'.$an.'"  and income = "'.$income.'" 
                $data = DB::connection('mysql')->select('  
                        select o.income,ifnull(n.icode,d.icode) as icode,ifnull(n.billcode,n.nhso_adp_code) as nhso_adp_code,ifnull(n.name,d.name) as dname,sum(o.qty) as qty,sum(sum_price) as sum_price
                        ,(SELECT sum(qty) from pkbackoffice.acc_ucep24 where an = o.an and icode = o.icode) as qty_ucep ,o.unitprice
                        ,(SELECT sum(sum_price) from pkbackoffice.acc_ucep24 where an = o.an and icode = o.icode) as price_ucep
                        from hos.opitemrece o
                        left outer join hos.nondrugitems n on n.icode = o.icode
                        left outer join hos.drugitems d on d.icode = o.icode
                        left outer join hos.income i on i.income = o.income
                        where o.an = "'.$an.'"
                        and o.income = "'.$income.'" 
                        group by o.icode
                        order by o.icode
                '); 

            return view('ucep.ucep24_income',[
                'startdate'        =>     $startdate,
                'enddate'          =>     $enddate, 
                'data'             =>     $data, 
                'an'               =>     $an, 
                'income'           =>     $income, 
            ]);
    }
    
    
   
 }