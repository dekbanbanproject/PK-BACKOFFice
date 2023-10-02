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
use App\Models\D_export_ucep;
use App\Models\Dtemp_hosucep;
use App\Models\D_ucep;
use App\Models\D_ins;
use App\Models\D_pat;
use App\Models\D_opd;
use App\Models\D_orf;
use App\Models\D_odx;
use App\Models\D_cht;
use App\Models\D_cha;
use App\Models\D_oop;
use App\Models\Tempexport;
use App\Models\D_adp;
use App\Models\D_dru;
use App\Models\D_idx;
use App\Models\D_iop;
use App\Models\D_ipd;
use App\Models\D_aer;
use App\Models\D_irf;
use App\Models\D_query;
use App\Models\D_ucep24_main;
use App\Models\D_ucep24;
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

    public function ucep24_claim(Request $request)
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

                $data_main = DB::connection('mysql')->select('SELECT * from d_ucep24_main');  
                $data = DB::connection('mysql')->select('SELECT * from d_ucep24 group by an');
                $data_opd = DB::connection('mysql')->select('SELECT * from d_opd'); 
                $data_orf = DB::connection('mysql')->select('SELECT * from d_orf'); 
                $data_oop = DB::connection('mysql')->select('SELECT * from d_oop');
                $data_odx = DB::connection('mysql')->select('SELECT * from d_odx');
                $data_idx = DB::connection('mysql')->select('SELECT * from d_idx');
                $data_ipd = DB::connection('mysql')->select('SELECT * from d_ipd');
                $data_irf = DB::connection('mysql')->select('SELECT * from d_irf');
            } else {
                $iduser = Auth::user()->id;
                D_ucep24_main::truncate();
                D_ucep24::truncate();
                D_opd::where('user_id','=',$iduser)->delete();
                D_orf::where('user_id','=',$iduser)->delete();
                D_oop::where('user_id','=',$iduser)->delete();
                D_odx::where('user_id','=',$iduser)->delete();
                D_idx::where('user_id','=',$iduser)->delete();
                D_ipd::where('user_id','=',$iduser)->delete();
                D_irf::where('user_id','=',$iduser)->delete();

                $data_opitem = DB::connection('mysql')->select('   
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
                        and e.er_emergency_level_id  in("1","2")                       
                        group BY i.an,o.icode,o.rxdate
                        ORDER BY i.an;
                ');                  
                foreach ($data_opitem as $key => $value) {    
                    D_ucep24::insert([
                        'vn'                => $value->vn,
                        'hn'                => $value->hn,
                        'an'                => $value->an, 
                        'vstdate'           => $value->vstdate,
                        'rxdate'            => $value->rxdate,
                        'dchdate'           => $value->dchdate, 
                        'icode'             => $value->icode,
                        'name'              => $value->namelist,
                        'qty'               => $value->qty,
                        'unitprice'         => $value->unitprice,
                        'sum_price'         => $value->sum_price, 
                        'user_id'           => Auth::user()->id 
                    ]);
                }

                $data_main_ = DB::connection('mysql')->select('   
                        SELECT a.vn,o.an,o.hn 
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
                        and e.er_emergency_level_id in("1","2")                       
                        group BY i.an
                        ORDER BY i.an;
                ');                 
                foreach ($data_main_ as $key => $value2) {    
                    D_ucep24_main::insert([
                        'vn'                => $value2->vn,
                        'hn'                => $value2->hn,
                        'an'                => $value2->an 
                    ]);
                }

                 //D_opd
                $data_opd = DB::connection('mysql')->select('
                    SELECT  v.hn HN
                    ,v.spclty CLINIC
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                    ,concat(substr(o.vsttime,1,2),substr(o.vsttime,4,2)) TIMEOPD
                    ,v.vn SEQ
                    ,"1" UUC 
                    from hos.vn_stat v
                    LEFT OUTER JOIN hos.ovst o on o.vn = v.vn
                    LEFT OUTER JOIN hos.pttype p on p.pttype = v.pttype
                    LEFT OUTER JOIN hos.ipt i on i.vn = v.vn
                    LEFT OUTER JOIN hos.patient pt on pt.hn = v.hn
                    WHERE v.vn IN(SELECT vn from pkbackoffice.d_ucep24_main)                    
                ');
                // LEFT JOIN d_export_ucep x on x.vn = v.vn
                //         where x.active="N";
                foreach ($data_opd as $val3) {            
                    $addo = new D_opd;  
                    $addo->HN             = $val3->HN;
                    $addo->CLINIC         = $val3->CLINIC;
                    $addo->DATEOPD        = $val3->DATEOPD;
                    $addo->TIMEOPD        = $val3->TIMEOPD;
                    $addo->SEQ            = $val3->SEQ;
                    $addo->UUC            = $val3->UUC; 
                    $addo->user_id        = $iduser;
                    $addo->save();
                }
                //D_orf
                $data_orf_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                    ,v.spclty CLINIC
                    ,ifnull(r1.refer_hospcode,r2.refer_hospcode) REFER
                    ,"0100" REFERTYPE
                    ,v.vn SEQ 
                    from hos.vn_stat v 
                    LEFT OUTER JOIN hos.referin r1 on r1.vn = v.vn
                    LEFT OUTER JOIN hos.referout r2 on r2.vn = v.vn
                    WHERE v.vn IN(SELECT vn from pkbackoffice.d_ucep24_main)
                    and (r1.vn is not null or r2.vn is not null);
                ');                
                foreach ($data_orf_ as $va4) {              
                    $addof = new D_orf;  
                    $addof->HN             = $va4->HN;
                    $addof->CLINIC         = $va4->CLINIC;
                    $addo->DATEOPD         = $va4->DATEOPD;
                    $addof->REFER          = $va4->REFER;
                    $addof->SEQ            = $va4->SEQ;
                    $addof->REFERTYPE      = $va4->REFERTYPE; 
                    $addof->user_id        = $iduser;
                    $addof->save();
                }
                //D_oop
                $data_oop_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                    ,v.spclty CLINIC
                    ,o.icd10 OPER
                    ,if(d.licenseno="","-99999",d.licenseno) DROPID
                    ,pt.cid PERSON_ID
                    ,v.vn SEQ 
                    from hos.vn_stat v
                    LEFT OUTER JOIN hos.ovstdiag o on o.vn = v.vn
                    LEFT OUTER JOIN hos.patient pt on v.hn=pt.hn
                    LEFT OUTER JOIN hos.doctor d on d.`code` = o.doctor
                    LEFT OUTER JOIN hos.icd9cm1 i on i.code = o.icd10
                    WHERE v.vn IN(SELECT vn from pkbackoffice.d_ucep24_main) 
                ');
                foreach ($data_oop_ as $va6) {
                    $addoop = new D_oop;  
                    $addoop->HN             = $va6->HN;
                    $addoop->CLINIC         = $va6->CLINIC;
                    $addoop->DATEOPD        = $va6->DATEOPD;
                    $addoop->OPER           = $va6->OPER;
                    $addoop->DROPID         = $va6->DROPID;
                    $addoop->PERSON_ID      = $va6->PERSON_ID; 
                    $addoop->SEQ            = $va6->SEQ; 
                    $addoop->user_id        = $iduser;
                    $addoop->save();
                    
                }
                 // D_odx
                $data_odx_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEDX
                    ,v.spclty CLINIC
                    ,o.icd10 DIAG
                    ,o.diagtype DXTYPE
                    ,if(d.licenseno="","-99999",d.licenseno) DRDX
                    ,v.cid PERSON_ID
                    ,v.vn SEQ 
                    from vn_stat v
                    LEFT OUTER JOIN ovstdiag o on o.vn = v.vn
                    LEFT OUTER JOIN doctor d on d.`code` = o.doctor
                    LEFT OUTER JOIN icd101 i on i.code = o.icd10
                    WHERE v.vn IN(SELECT vn from pkbackoffice.d_ucep24_main) 
                ');
                foreach ($data_odx_ as $va5) {
                    $adddx = new D_odx;  
                    $adddx->HN             = $va5->HN;
                    $adddx->CLINIC         = $va5->CLINIC;
                    $adddx->DATEDX         = $va5->DATEDX;
                    $adddx->DIAG           = $va5->DIAG;
                    $adddx->DXTYPE         = $va5->DXTYPE;
                    $adddx->DRDX           = $va5->DRDX; 
                    $adddx->PERSON_ID      = $va5->PERSON_ID; 
                    $adddx->SEQ            = $va5->SEQ; 
                    $adddx->user_id        = $iduser;
                    $adddx->save();
                    
                }
                //D_idx
                $data_idx_ = DB::connection('mysql2')->select('
                    SELECT i2.AN,i1.icd10 as DIAG,i1.diagtype as DXTYPE , d.licenseno as DRDX,dx.nhso_code as dx_type_code  
                    FROM hos.ipt i2  
                    LEFT OUTER JOIN hos.iptdiag i1 on i1.an= i2.an  
                    LEFT OUTER JOIN hos.diagtype dx on dx.diagtype = i1.diagtype  
                    LEFT OUTER JOIN hos.doctor d on d.code=i1.doctor  
                    WHERE  i2.an IN(SELECT an from pkbackoffice.d_ucep24_main)
                ');
                foreach ($data_idx_ as $va7) {
                    $addidrx = new D_idx; 
                    $addidrx->AN             = $va7->AN;
                    $addidrx->DIAG           = $va7->DIAG;
                    $addidrx->DXTYPE         = $va7->DXTYPE;
                    $addidrx->DRDX           = $va7->DRDX; 
                    $addidrx->user_id        = $iduser;
                    $addidrx->save();
                            
                }
                //D_ipd
                $data_ipd_ = DB::connection('mysql2')->select('
                    SELECT a.hn HN,a.an AN
                    ,DATE_FORMAT(o.regdate,"%Y%m%d") DATEADM
                    ,Time_format(o.regtime,"%H%i") TIMEADM
                    ,DATE_FORMAT(o.dchdate,"%Y%m%d") DATEDSC
                    ,Time_format(o.dchtime,"%H%i")  TIMEDSC
                    ,right(o.dchstts,1) DISCHS
                    ,right(o.dchtype,1) DISCHT
                    ,o.ward WARDDSC,o.spclty DEPT
                    ,format(o.bw/1000,3) ADM_W
                    ,"1" UUC ,"I" SVCTYPE 
                    FROM hos.an_stat a
                    LEFT OUTER JOIN hos.ipt o on o.an = a.an
                    LEFT OUTER JOIN hos.pttype p on p.pttype = a.pttype
                    LEFT OUTER JOIN hos.patient pt on pt.hn = a.hn 
                    WHERE  a.an IN(SELECT an from pkbackoffice.d_ucep24_main) 
                ');
                foreach ($data_ipd_ as $va10) {                
                    $addipd = new D_ipd; 
                    $addipd->AN             = $va10->AN;
                    $addipd->HN             = $va10->HN;
                    $addipd->DATEADM        = $va10->DATEADM;
                    $addipd->TIMEADM        = $va10->TIMEADM; 
                    $addipd->DATEDSC        = $va10->DATEDSC; 
                    $addipd->TIMEDSC        = $va10->TIMEDSC; 
                    $addipd->DISCHS         = $va10->DISCHS; 
                    $addipd->DISCHT         = $va10->DISCHT; 
                    $addipd->DEPT           = $va10->DEPT; 
                    $addipd->ADM_W          = $va10->ADM_W; 
                    $addipd->UUC            = $va10->UUC; 
                    $addipd->SVCTYPE        = $va10->SVCTYPE; 
                    $addipd->user_id        = $iduser;
                    $addipd->save();
                }
                  //D_irf
                $data_irf_ = DB::connection('mysql2')->select('
                    SELECT a.an AN
                    ,ifnull(o.refer_hospcode,oo.refer_hospcode) REFER
                    ,"0100" REFERTYPE 
                    FROM hos.an_stat a
                    LEFT OUTER JOIN hos.referout o on o.vn =a.an
                    LEFT OUTER JOIN hos.referin oo on oo.vn =a.an
                    LEFT OUTER JOIN hos.ipt ip on ip.an = a.an 
                    WHERE a.an IN(SELECT an from pkbackoffice.d_ucep24_main)  
                    and (a.an in(select vn from hos.referin where vn = oo.vn) or a.an in(select vn from hos.referout where vn = o.vn)); 
                ');
                foreach ($data_irf_ as $va11) {
                    D_irf::insert([
                        'AN'                 => $va11->AN,
                        'REFER'              => $va11->REFER,
                        'REFERTYPE'          => $va11->REFERTYPE,
                        'user_id'            => $iduser,
                    ]);
                }

                $data_main = DB::connection('mysql')->select('SELECT * from d_ucep24_main');  
                $data = DB::connection('mysql')->select('SELECT * from d_ucep24 group by an');
                $data_opd = DB::connection('mysql')->select('SELECT * from d_opd');  
                $data_orf = DB::connection('mysql')->select('SELECT * from d_orf'); 
                $data_oop = DB::connection('mysql')->select('SELECT * from d_oop'); 
                $data_odx = DB::connection('mysql')->select('SELECT * from d_odx');
                $data_idx = DB::connection('mysql')->select('SELECT * from d_idx');
                $data_ipd = DB::connection('mysql')->select('SELECT * from d_ipd');
                $data_irf = DB::connection('mysql')->select('SELECT * from d_irf');
            }
                  
            return view('ucep.ucep24_claim',[
                'startdate'        =>     $startdate,
                'enddate'          =>     $enddate, 
                'data'             =>     $data, 
                'data_main'        =>     $data_main,
                'data_opd'         =>     $data_opd,
                'data_orf'         =>     $data_orf,
                'data_oop'         =>     $data_oop,
                'data_odx'         =>     $data_odx,
                'data_idx'         =>     $data_idx,
                'data_ipd'         =>     $data_ipd,
                'data_irf'         =>     $data_irf,
            ]);
    }
    
    
   
 }