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
                $data_aer = DB::connection('mysql')->select('SELECT * from d_aer');
                $data_iop = DB::connection('mysql')->select('SELECT * from d_iop');
                $data_adp = DB::connection('mysql')->select('SELECT * from d_adp');
            } else {
                $iduser = Auth::user()->id;
                D_ucep24_main::truncate();
                D_ucep24::truncate();
                // D_opd::where('user_id','=',$iduser)->delete();
                // D_orf::where('user_id','=',$iduser)->delete();
                // D_oop::where('user_id','=',$iduser)->delete();
                // D_odx::where('user_id','=',$iduser)->delete();
                // D_idx::where('user_id','=',$iduser)->delete();
                // D_ipd::where('user_id','=',$iduser)->delete();
                // D_irf::where('user_id','=',$iduser)->delete();
                

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

                //  //D_opd
                // $data_opd = DB::connection('mysql')->select('
                //     SELECT  v.hn HN
                //     ,v.spclty CLINIC
                //     ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                //     ,concat(substr(o.vsttime,1,2),substr(o.vsttime,4,2)) TIMEOPD
                //     ,v.vn SEQ
                //     ,"1" UUC 
                //     from hos.vn_stat v
                //     LEFT OUTER JOIN hos.ovst o on o.vn = v.vn
                //     LEFT OUTER JOIN hos.pttype p on p.pttype = v.pttype
                //     LEFT OUTER JOIN hos.ipt i on i.vn = v.vn
                //     LEFT OUTER JOIN hos.patient pt on pt.hn = v.hn
                //     WHERE v.vn IN(SELECT vn from pkbackoffice.d_ucep24_main)                    
                // ');
                // // LEFT JOIN d_export_ucep x on x.vn = v.vn
                // //         where x.active="N";
                // foreach ($data_opd as $val3) {            
                //     $addo = new D_opd;  
                //     $addo->HN             = $val3->HN;
                //     $addo->CLINIC         = $val3->CLINIC;
                //     $addo->DATEOPD        = $val3->DATEOPD;
                //     $addo->TIMEOPD        = $val3->TIMEOPD;
                //     $addo->SEQ            = $val3->SEQ;
                //     $addo->UUC            = $val3->UUC; 
                //     $addo->user_id        = $iduser;
                //     $addo->save();
                // }
                // //D_orf
                // $data_orf_ = DB::connection('mysql2')->select('
                //     SELECT v.hn HN
                //     ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                //     ,v.spclty CLINIC
                //     ,ifnull(r1.refer_hospcode,r2.refer_hospcode) REFER
                //     ,"0100" REFERTYPE
                //     ,v.vn SEQ 
                //     from hos.vn_stat v 
                //     LEFT OUTER JOIN hos.referin r1 on r1.vn = v.vn
                //     LEFT OUTER JOIN hos.referout r2 on r2.vn = v.vn
                //     WHERE v.vn IN(SELECT vn from pkbackoffice.d_ucep24_main)
                //     and (r1.vn is not null or r2.vn is not null);
                // ');                
                // foreach ($data_orf_ as $va4) {              
                //     $addof = new D_orf;  
                //     $addof->HN             = $va4->HN;
                //     $addof->CLINIC         = $va4->CLINIC;
                //     $addof->DATEOPD         = $va4->DATEOPD;
                //     $addof->REFER          = $va4->REFER;
                //     $addof->SEQ            = $va4->SEQ;
                //     $addof->REFERTYPE      = $va4->REFERTYPE; 
                //     $addof->user_id        = $iduser;
                //     $addof->save();
                // }
                // //D_oop
                // $data_oop_ = DB::connection('mysql2')->select('
                //     SELECT v.hn HN
                //     ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                //     ,v.spclty CLINIC
                //     ,o.icd10 OPER
                //     ,if(d.licenseno="","-99999",d.licenseno) DROPID
                //     ,pt.cid PERSON_ID
                //     ,v.vn SEQ 
                //     from hos.vn_stat v
                //     LEFT OUTER JOIN hos.ovstdiag o on o.vn = v.vn
                //     LEFT OUTER JOIN hos.patient pt on v.hn=pt.hn
                //     LEFT OUTER JOIN hos.doctor d on d.`code` = o.doctor
                //     LEFT OUTER JOIN hos.icd9cm1 i on i.code = o.icd10
                //     WHERE v.vn IN(SELECT vn from pkbackoffice.d_ucep24_main) 
                // ');
                // foreach ($data_oop_ as $va6) {
                //     $addoop = new D_oop;  
                //     $addoop->HN             = $va6->HN;
                //     $addoop->CLINIC         = $va6->CLINIC;
                //     $addoop->DATEOPD        = $va6->DATEOPD;
                //     $addoop->OPER           = $va6->OPER;
                //     $addoop->DROPID         = $va6->DROPID;
                //     $addoop->PERSON_ID      = $va6->PERSON_ID; 
                //     $addoop->SEQ            = $va6->SEQ; 
                //     $addoop->user_id        = $iduser;
                //     $addoop->save();
                    
                // }
                // // D_odx
                // $data_odx_ = DB::connection('mysql2')->select('
                //     SELECT v.hn HN
                //     ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEDX
                //     ,v.spclty CLINIC
                //     ,o.icd10 DIAG
                //     ,o.diagtype DXTYPE
                //     ,if(d.licenseno="","-99999",d.licenseno) DRDX
                //     ,v.cid PERSON_ID
                //     ,v.vn SEQ 
                //     from vn_stat v
                //     LEFT OUTER JOIN ovstdiag o on o.vn = v.vn
                //     LEFT OUTER JOIN doctor d on d.`code` = o.doctor
                //     LEFT OUTER JOIN icd101 i on i.code = o.icd10
                //     WHERE v.vn IN(SELECT vn from pkbackoffice.d_ucep24_main) 
                // ');
                // foreach ($data_odx_ as $va5) {
                //     $adddx = new D_odx;  
                //     $adddx->HN             = $va5->HN;
                //     $adddx->CLINIC         = $va5->CLINIC;
                //     $adddx->DATEDX         = $va5->DATEDX;
                //     $adddx->DIAG           = $va5->DIAG;
                //     $adddx->DXTYPE         = $va5->DXTYPE;
                //     $adddx->DRDX           = $va5->DRDX; 
                //     $adddx->PERSON_ID      = $va5->PERSON_ID; 
                //     $adddx->SEQ            = $va5->SEQ; 
                //     $adddx->user_id        = $iduser;
                //     $adddx->save();
                    
                // }
                // //D_idx
                // $data_idx_ = DB::connection('mysql2')->select('
                //     SELECT i2.AN,i1.icd10 as DIAG,i1.diagtype as DXTYPE , d.licenseno as DRDX,dx.nhso_code as dx_type_code  
                //     FROM hos.ipt i2  
                //     LEFT OUTER JOIN hos.iptdiag i1 on i1.an= i2.an  
                //     LEFT OUTER JOIN hos.diagtype dx on dx.diagtype = i1.diagtype  
                //     LEFT OUTER JOIN hos.doctor d on d.code=i1.doctor  
                //     WHERE  i2.an IN(SELECT an from pkbackoffice.d_ucep24_main)
                // ');
                // foreach ($data_idx_ as $va7) {
                //     $addidrx = new D_idx; 
                //     $addidrx->AN             = $va7->AN;
                //     $addidrx->DIAG           = $va7->DIAG;
                //     $addidrx->DXTYPE         = $va7->DXTYPE;
                //     $addidrx->DRDX           = $va7->DRDX; 
                //     $addidrx->user_id        = $iduser;
                //     $addidrx->save();
                            
                // }
                // //D_ipd
                // $data_ipd_ = DB::connection('mysql2')->select('
                //     SELECT a.hn HN,a.an AN
                //     ,DATE_FORMAT(o.regdate,"%Y%m%d") DATEADM
                //     ,Time_format(o.regtime,"%H%i") TIMEADM
                //     ,DATE_FORMAT(o.dchdate,"%Y%m%d") DATEDSC
                //     ,Time_format(o.dchtime,"%H%i")  TIMEDSC
                //     ,right(o.dchstts,1) DISCHS
                //     ,right(o.dchtype,1) DISCHT
                //     ,o.ward WARDDSC,o.spclty DEPT
                //     ,format(o.bw/1000,3) ADM_W
                //     ,"1" UUC ,"I" SVCTYPE 
                //     FROM hos.an_stat a
                //     LEFT OUTER JOIN hos.ipt o on o.an = a.an
                //     LEFT OUTER JOIN hos.pttype p on p.pttype = a.pttype
                //     LEFT OUTER JOIN hos.patient pt on pt.hn = a.hn 
                //     WHERE  a.an IN(SELECT an from pkbackoffice.d_ucep24_main) 
                // ');
                // foreach ($data_ipd_ as $va10) {                
                //     $addipd = new D_ipd; 
                //     $addipd->AN             = $va10->AN;
                //     $addipd->HN             = $va10->HN;
                //     $addipd->DATEADM        = $va10->DATEADM;
                //     $addipd->TIMEADM        = $va10->TIMEADM; 
                //     $addipd->DATEDSC        = $va10->DATEDSC; 
                //     $addipd->TIMEDSC        = $va10->TIMEDSC; 
                //     $addipd->DISCHS         = $va10->DISCHS; 
                //     $addipd->DISCHT         = $va10->DISCHT; 
                //     $addipd->DEPT           = $va10->DEPT; 
                //     $addipd->ADM_W          = $va10->ADM_W; 
                //     $addipd->UUC            = $va10->UUC; 
                //     $addipd->SVCTYPE        = $va10->SVCTYPE; 
                //     $addipd->user_id        = $iduser;
                //     $addipd->save();
                // }
                // //D_irf
                // $data_irf_ = DB::connection('mysql2')->select('
                //     SELECT a.an AN
                //     ,ifnull(o.refer_hospcode,oo.refer_hospcode) REFER
                //     ,"0100" REFERTYPE 
                //     FROM hos.an_stat a
                //     LEFT OUTER JOIN hos.referout o on o.vn =a.an
                //     LEFT OUTER JOIN hos.referin oo on oo.vn =a.an
                //     LEFT OUTER JOIN hos.ipt ip on ip.an = a.an 
                //     WHERE a.an IN(SELECT an from pkbackoffice.d_ucep24_main)  
                //     and (a.an in(select vn from hos.referin where vn = oo.vn) or a.an in(select vn from hos.referout where vn = o.vn)); 
                // ');
                // foreach ($data_irf_ as $va11) {
                //     D_irf::insert([
                //         'AN'                 => $va11->AN,
                //         'REFER'              => $va11->REFER,
                //         'REFERTYPE'          => $va11->REFERTYPE,
                //         'user_id'            => $iduser,
                //     ]);
                // }

                $data_main = DB::connection('mysql')->select('SELECT * from d_ucep24_main');  
                $data = DB::connection('mysql')->select('SELECT * from d_ucep24 group by an');
                $data_opd = DB::connection('mysql')->select('SELECT * from d_opd');  
                $data_orf = DB::connection('mysql')->select('SELECT * from d_orf'); 
                $data_oop = DB::connection('mysql')->select('SELECT * from d_oop'); 
                $data_odx = DB::connection('mysql')->select('SELECT * from d_odx');
                $data_idx = DB::connection('mysql')->select('SELECT * from d_idx');
                $data_ipd = DB::connection('mysql')->select('SELECT * from d_ipd');
                $data_irf = DB::connection('mysql')->select('SELECT * from d_irf');
                $data_aer = DB::connection('mysql')->select('SELECT * from d_aer');
                $data_iop = DB::connection('mysql')->select('SELECT * from d_iop');
                $data_adp = DB::connection('mysql')->select('SELECT * from d_adp');
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
                'data_aer'         =>     $data_aer,
                'data_iop'         =>     $data_iop,
                'data_adp'         =>     $data_adp,
            ]);
    }
    public function ucep24_claim_process(Request $request)
    { 
        $data_vn_1 = DB::connection('mysql')->select('SELECT vn,an from pkbackoffice.d_ucep24_main');
        $iduser = Auth::user()->id;
       
        D_opd::where('user_id','=',$iduser)->delete();
        D_orf::where('user_id','=',$iduser)->delete();
        D_oop::where('user_id','=',$iduser)->delete();
        D_odx::where('user_id','=',$iduser)->delete();
        D_idx::where('user_id','=',$iduser)->delete();
        D_ipd::where('user_id','=',$iduser)->delete();
        D_irf::where('user_id','=',$iduser)->delete();
        D_aer::where('user_id','=',$iduser)->delete();
        D_iop::where('user_id','=',$iduser)->delete();
        D_adp::where('user_id','=',$iduser)->delete();   
        D_dru::where('user_id','=',$iduser)->delete();   
        D_pat::where('user_id','=',$iduser)->delete();
        D_cht::where('user_id','=',$iduser)->delete();
        D_cha::where('user_id','=',$iduser)->delete();
        D_ins::where('user_id','=',$iduser)->delete();

         foreach ($data_vn_1 as $key => $va1) {
                 //D_irf
                 $data_irf_ = DB::connection('mysql2')->select('
                    SELECT a.an AN
                    ,ifnull(o.refer_hospcode,oo.refer_hospcode) REFER
                    ,"0100" REFERTYPE 
                    FROM hos.an_stat a
                    LEFT OUTER JOIN hos.referout o on o.vn =a.an
                    LEFT OUTER JOIN hos.referin oo on oo.vn =a.an
                    LEFT OUTER JOIN hos.ipt ip on ip.an = a.an 
                    WHERE a.an IN("'.$va1->an.'")  
                    and (a.an in(select vn from hos.referin where vn = oo.vn) or a.an in(select vn from hos.referout where vn = o.vn)); 
                ');
                foreach ($data_irf_ as $va12) {
                    D_irf::insert([
                        'AN'                 => $va12->AN,
                        'REFER'              => $va12->REFER,
                        'REFERTYPE'          => $va12->REFERTYPE,
                        'user_id'            => $iduser,
                    ]);
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
                    WHERE  a.an IN("'.$va1->an.'")
                ');
                foreach ($data_ipd_ as $va13) {                
                    $addipd = new D_ipd; 
                    $addipd->AN             = $va13->AN;
                    $addipd->HN             = $va13->HN;
                    $addipd->DATEADM        = $va13->DATEADM;
                    $addipd->TIMEADM        = $va13->TIMEADM; 
                    $addipd->DATEDSC        = $va13->DATEDSC; 
                    $addipd->TIMEDSC        = $va13->TIMEDSC; 
                    $addipd->DISCHS         = $va13->DISCHS; 
                    $addipd->DISCHT         = $va13->DISCHT; 
                    $addipd->DEPT           = $va13->DEPT; 
                    $addipd->ADM_W          = $va13->ADM_W; 
                    $addipd->UUC            = $va13->UUC; 
                    $addipd->SVCTYPE        = $va13->SVCTYPE; 
                    $addipd->user_id        = $iduser;
                    $addipd->save();
                }
                //D_idx
                $data_idx_ = DB::connection('mysql2')->select('
                    SELECT i2.AN,i1.icd10 as DIAG,i1.diagtype as DXTYPE , d.licenseno as DRDX,dx.nhso_code as dx_type_code  
                    FROM hos.ipt i2  
                    LEFT OUTER JOIN hos.iptdiag i1 on i1.an= i2.an  
                    LEFT OUTER JOIN hos.diagtype dx on dx.diagtype = i1.diagtype  
                    LEFT OUTER JOIN hos.doctor d on d.code=i1.doctor  
                    WHERE  i2.an IN("'.$va1->an.'")
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
                // D_odx
                $data_odx_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEDX
                    ,v.spclty CLINIC
                    ,o.icd10 DIAG
                    ,o.diagtype DXTYPE 
                    ,CASE 
                    WHEN d.licenseno IS NULL THEN "ว33980"
                    WHEN d.licenseno LIKE "-%" THEN "ว69577"
                    WHEN d.licenseno LIKE "พ%" THEN "ว33985" 
                    ELSE "ว33985" 
                    END as DRDX
                    ,v.cid PERSON_ID
                    ,v.vn SEQ 
                    from vn_stat v
                    LEFT OUTER JOIN ovstdiag o on o.vn = v.vn
                    LEFT OUTER JOIN doctor d on d.`code` = o.doctor
                    LEFT OUTER JOIN icd101 i on i.code = o.icd10
                    WHERE v.vn IN("'.$va1->vn.'")
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
                //D_oop
                $data_oop_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                    ,v.spclty CLINIC
                    ,o.icd10 OPER 
                    ,CASE 
                    WHEN d.licenseno IS NULL THEN "ว33980"
                    WHEN d.licenseno LIKE "-%" THEN "ว69577"
                    WHEN d.licenseno LIKE "พ%" THEN "ว33985" 
                    ELSE "ว33985" 
                    END as DROPID
                    ,pt.cid PERSON_ID
                    ,v.vn SEQ 
                    from hos.vn_stat v
                    LEFT OUTER JOIN hos.ovstdiag o on o.vn = v.vn
                    LEFT OUTER JOIN hos.patient pt on v.hn=pt.hn
                    LEFT OUTER JOIN hos.doctor d on d.`code` = o.doctor
                    LEFT OUTER JOIN hos.icd9cm1 i on i.code = o.icd10
                    WHERE v.vn IN("'.$va1->vn.'")
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
                    WHERE v.vn IN("'.$va1->vn.'") 
                    and (r1.vn is not null or r2.vn is not null);
                ');                
                foreach ($data_orf_ as $va4) {              
                    $addof = new D_orf;  
                    $addof->HN             = $va4->HN;
                    $addof->CLINIC         = $va4->CLINIC;
                    $addof->DATEOPD         = $va4->DATEOPD;
                    $addof->REFER          = $va4->REFER;
                    $addof->SEQ            = $va4->SEQ;
                    $addof->REFERTYPE      = $va4->REFERTYPE; 
                    $addof->user_id        = $iduser;
                    $addof->save();
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
                    WHERE v.vn IN("'.$va1->vn.'")                  
                '); 
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
                 //D_aer
                $data_aer_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                    ,i.an AN
                    ,v.vstdate DATEOPD
                    ,vv.claim_code AUTHAE
                    ,"" AEDATE,"" AETIME,"" AETYPE,"" REFER_NO,"" REFMAINI
                    ,"" IREFTYPE,"" REFMAINO,"" OREFTYPE,"" UCAE,"" EMTYPE,v.vn SEQ
                    ,"" AESTATUS,"" DALERT,"" TALERT
                    from hos.vn_stat v
                    left outer join hos.ipt i on i.vn = v.vn
                    left outer join hos.visit_pttype vv on vv.vn = v.vn
                    left outer join hos.pttype pt on pt.pttype =v.pttype
                    WHERE v.vn IN("'.$va1->vn.'")
                    and i.an is null
                    GROUP BY v.vn
                    union all
                    SELECT a.hn HN
                    ,a.an AN
                    ,a.dchdate DATEOPD
                    ,vv.claim_code AUTHAE
                    ,"" AEDATE,"" AETIME,"" AETYPE,"" REFER_NO,"" REFMAINI
                    ,"" IREFTYPE,"" REFMAINO,"" OREFTYPE,"" UCAE,"" EMTYPE,"" SEQ
                    ,"" AESTATUS,"" DALERT,"" TALERT
                    from hos.an_stat a
                    left outer join hos.ipt_pttype vv on vv.an = a.an
                    left outer join hos.pttype pt on pt.pttype =a.pttype
                    WHERE a.an IN("'.$va1->an.'")
                    group by a.an;
                ');
                foreach ($data_aer_ as $va8) {
                    D_aer::insert([
                        'HN'                => $va8->HN,
                        'AN'                => $va8->AN,
                        'DATEOPD'           => $va8->DATEOPD,
                        'AUTHAE'            => $va8->AUTHAE,
                        'AEDATE'            => $va8->AEDATE,
                        'AETIME'            => $va8->AETIME,
                        'AETYPE'            => $va8->AETYPE,
                        'REFER_NO'          => $va8->REFER_NO,
                        'REFMAINI'          => $va8->REFMAINI,
                        'IREFTYPE'          => $va8->IREFTYPE,
                        'REFMAINO'          => $va8->REFMAINO,
                        'OREFTYPE'          => $va8->OREFTYPE,
                        'UCAE'              => $va8->UCAE,
                        'SEQ'               => $va8->SEQ,
                        'AESTATUS'          => $va8->AESTATUS,
                        'DALERT'            => $va8->DALERT,
                        'TALERT'            => $va8->TALERT,
                        'user_id'           => $iduser,
                    ]);
                }
                 //D_iop 
                $data_iop_ = DB::connection('mysql2')->select('
                    SELECT v.an AN
                    ,o.icd9 OPER
                    ,o.oper_type as OPTYPE 
                    ,CASE 
                    WHEN d.licenseno IS NULL THEN "ว33980"
                    WHEN d.licenseno LIKE "-%" THEN "ว69577"
                    WHEN d.licenseno LIKE "พ%" THEN "ว33985" 
                    ELSE "ว33985" 
                    END as DROPID
                    ,DATE_FORMAT(o.opdate,"%Y%m%d") DATEIN
                    ,Time_format(o.optime,"%H%i") TIMEIN
                    ,DATE_FORMAT(o.enddate,"%Y%m%d") DATEOUT
                    ,Time_format(o.endtime,"%H%i") TIMEOUT
                    FROM an_stat v
                    left outer join iptoprt o on o.an = v.an
                    left outer join doctor d on d.`code` = o.doctor
                    left outer join icd9cm1 i on i.code = o.icd9
                    left outer join ipt ip on ip.an = v.an
                    WHERE v.vn IN("'.$va1->vn.'")
                ');
                foreach ($data_iop_ as $va9) {
                    D_iop::insert([
                        'AN'                => $va9->AN,
                        'OPER'              => $va9->OPER,
                        'OPTYPE'            => $va9->OPTYPE,
                        'DROPID'            => $va9->DROPID,
                        'DATEIN'            => $va9->DATEIN,
                        'TIMEIN'            => $va9->TIMEIN,
                        'DATEOUT'           => $va9->DATEOUT,
                        'TIMEOUT'           => $va9->TIMEOUT,
                        'user_id'           => $iduser,
                    ]);
                }
                //D_adp
                $data_adp_ = DB::connection('mysql2')->select('
                    SELECT HN,AN,DATEOPD,TYPE,CODE,sum(QTY) QTY,RATE,SEQ
                    ,"" CAGCODE,"" DOSE,"" CA_TYPE,""SERIALNO,"0" TOTCOPAY,""USE_STATUS,"0" TOTAL,""QTYDAY
                    ,"" TMLTCODE ,"" STATUS1 ,"" BI ,"" CLINIC ,"" ITEMSRC ,"" PROVIDER
                    ,"" GLAVIDA ,"" GA_WEEK ,"" DCIP ,"0000-00-00" LMP ,""SP_ITEM,icode ,vstdate
                    from
                    (SELECT v.hn HN
                    ,if(v.an is null,"",v.an) AN
                    ,DATE_FORMAT(v.rxdate,"%Y%m%d") DATEOPD
                    ,n.nhso_adp_type_id TYPE
                    ,n.nhso_adp_code CODE 
                    ,sum(v.QTY) QTY
                    ,round(v.unitprice,2) RATE
                    ,if(v.an is null,v.vn,"") SEQ
                    ,"" CAGCODE,"" DOSE,"" CA_TYPE,""SERIALNO,"0" TOTCOPAY,""USE_STATUS,"0" TOTAL,""QTYDAY
                    ,"" TMLTCODE ,"" STATUS1 ,"" BI ,"" CLINIC ,"" ITEMSRC
                    ,"" PROVIDER ,"" GLAVIDA ,"" GA_WEEK ,"" DCIP ,"0000-00-00" LMP ,""SP_ITEM,v.icode,v.vstdate
                    from hos.opitemrece v
                    inner JOIN hos.nondrugitems n on n.icode = v.icode and n.nhso_adp_code is not null
                    left join hos.ipt i on i.an = v.an
                    AND i.an is not NULL 
                    WHERE i.vn IN("'.$va1->vn.'")
                    GROUP BY i.vn,n.nhso_adp_code,rate) a 
                    GROUP BY an,CODE,rate
                    UNION
                    SELECT HN,AN,DATEOPD,TYPE,CODE,sum(QTY) QTY,RATE,SEQ
                    ,"" CAGCODE,"" DOSE,"" CA_TYPE,""SERIALNO,"0" TOTCOPAY,""USE_STATUS,"0" TOTAL,""QTYDAY
                    ,"" TMLTCODE ,"" STATUS1 ,"" BI ,"" CLINIC ,"" ITEMSRC ,"" PROVIDER
                    ,"" GLAVIDA ,"" GA_WEEK ,"" DCIP ,"0000-00-00" LMP ,""SP_ITEM,icode ,vstdate
                    from
                    (SELECT v.hn HN
                    ,if(v.an is null,"",v.an) AN
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                    ,n.nhso_adp_type_id TYPE
                    ,n.nhso_adp_code CODE 
                    ,sum(v.QTY) QTY
                    ,round(v.unitprice,2) RATE
                    ,if(v.an is null,v.vn,"") SEQ
                    ,"" CAGCODE,"" DOSE,"" CA_TYPE,""SERIALNO,"0" TOTCOPAY,""USE_STATUS,"0" TOTAL,""QTYDAY
                    ,"" TMLTCODE ,"" STATUS1 ,"" BI ,"" CLINIC ,"" ITEMSRC ,"" PROVIDER
                    ,"" GLAVIDA ,"" GA_WEEK ,"" DCIP ,"0000-00-00" LMP ,""SP_ITEM,v.icode,v.vstdate
                    from hos.opitemrece v
                    inner JOIN hos.nondrugitems n on n.icode = v.icode and n.nhso_adp_code is not null
                    left join hos.vn_stat vv on vv.vn = v.vn
                    WHERE vv.vn IN("'.$va1->vn.'")
                    AND v.an is NULL
                    GROUP BY vv.vn,n.nhso_adp_code,rate) b 
                    GROUP BY seq,CODE,rate;                
                ');
                foreach ($data_adp_ as $va10) {
                    d_adp::insert([
                        'HN'                   => $va10->HN,
                        'AN'                   => $va10->AN,
                        'DATEOPD'              => $va10->DATEOPD,
                        'TYPE'                 => $va10->TYPE,
                        'CODE'                 => $va10->CODE,
                        'QTY'                  => $va10->QTY,
                        'RATE'                 => $va10->RATE,
                        'SEQ'                  => $va10->SEQ,
                        'CAGCODE'              => $va10->CAGCODE,
                        'DOSE'                 => $va10->DOSE,
                        'CA_TYPE'              => $va10->CA_TYPE,
                        'SERIALNO'             => $va10->SERIALNO,
                        'TOTCOPAY'             => $va10->TOTCOPAY,
                        'USE_STATUS'           => $va10->USE_STATUS,
                        'TOTAL'                => $va10->TOTAL,
                        'QTYDAY'               => $va10->QTYDAY,
                        'TMLTCODE'             => $va10->TMLTCODE,
                        'STATUS1'              => $va10->STATUS1,
                        'BI'                   => $va10->BI,
                        'CLINIC'               => $va10->CLINIC,
                        'ITEMSRC'              => $va10->ITEMSRC,
                        'PROVIDER'             => $va10->PROVIDER,
                        'GLAVIDA'              => $va10->GLAVIDA,
                        'GA_WEEK'              => $va10->GA_WEEK,
                        'DCIP'                 => $va10->DCIP,
                        'LMP'                  => $va10->LMP,
                        'SP_ITEM'              => $va10->SP_ITEM,
                        'icode'                => $va10->icode,
                        'vstdate'              => $va10->vstdate,
                        'user_id'              => $iduser,
                    ]);
                }
                //D_dru
                $data_dru_ = DB::connection('mysql2')->select('
                    SELECT vv.hcode HCODE
                    ,v.hn HN
                    ,v.an AN
                    ,vv.spclty CLINIC
                    ,vv.cid PERSON_ID
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATE_SERV
                    ,d.icode DID
                    ,concat(d.`name`," ",d.strength," ",d.units) DIDNAME
                    ,sum(v.qty) AMOUNT
                    ,round(v.unitprice,2) DRUGPRIC
                    ,"0.00" DRUGCOST
                    ,d.did DIDSTD
                    ,d.units UNIT
                    ,concat(d.packqty,"x",d.units) UNIT_PACK
                    ,v.vn SEQ
                    ,oo.presc_reason DRUGREMARK
                    ,"" PA_NO
                    ,"" TOTCOPAY
                    ,if(v.item_type="H","2","1") USE_STATUS
                    ,"" TOTAL,""SIGCODE,"" SIGTEXT,""  PROVIDER
                    from hos.opitemrece v
                    LEFT JOIN hos.drugitems d on d.icode = v.icode
                    LEFT JOIN hos.vn_stat vv on vv.vn = v.vn
                    LEFT JOIN hos.ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode
                    WHERE v.vn IN("'.$va1->vn.'")
                    and d.did is not null 
                    GROUP BY v.vn,did

                    UNION all

                    SELECT pt.hcode HCODE
                    ,v.hn HN
                    ,v.an AN
                    ,v1.spclty CLINIC
                    ,pt.cid PERSON_ID
                    ,DATE_FORMAT((v.vstdate),"%Y%m%d") DATE_SERV
                    ,d.icode DID
                    ,concat(d.`name`,"",d.strength," ",d.units) DIDNAME
                    ,sum(v.qty) AMOUNT
                    ,round(v.unitprice,2) DRUGPRIC
                    ,"0.00" DRUGCOST
                    ,d.did DIDSTD
                    ,d.units UNIT
                    ,concat(d.packqty,"x",d.units) UNIT_PACK
                    ,ifnull(v.vn,v.an) SEQ
                    ,oo.presc_reason DRUGREMARK
                    ,"" PA_NO
                    ,"" TOTCOPAY
                    ,if(v.item_type="H","2","1") USE_STATUS
                    ,"" TOTAL,""SIGCODE,"" SIGTEXT,""  PROVIDER
                    from hos.opitemrece v
                    LEFT JOIN hos.drugitems d on d.icode = v.icode
                    LEFT JOIN hos.patient pt  on v.hn = pt.hn
                    inner JOIN hos.ipt v1 on v1.an = v.an
                    LEFT JOIN hos.ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode
                    WHERE v1.vn IN("'.$va1->vn.'")
                    and d.did is not null AND v.qty<>"0"
                    GROUP BY v.an,d.icode,USE_STATUS;               
                ');
                foreach ($data_dru_ as $va11) {
                    D_dru::insert([ 
                        'HN'             => $va11->HN,
                        'CLINIC'         => $va11->CLINIC,
                        'HCODE'          => $va11->HCODE,
                        'AN'             => $va11->AN,
                        'PERSON_ID'      => $va11->PERSON_ID,
                        'DATE_SERV'      => $va11->DATE_SERV,
                        'DID'            => $va11->DID,
                        'DIDNAME'        => $va11->DIDNAME, 
                        'AMOUNT'         => $va11->AMOUNT,
                        'DRUGPRIC'       => $va11->DRUGPRIC,
                        'DRUGCOST'       => $va11->DRUGCOST,
                        'DIDSTD'         => $va11->DIDSTD,
                        'UNIT'           => $va11->UNIT,
                        'UNIT_PACK'      =>$va11->UNIT_PACK,
                        'SEQ'            => $va11->SEQ,
                        'DRUGREMARK'     => $va11->DRUGREMARK,
                        'PA_NO'          => $va11->PA_NO,
                        'TOTCOPAY'       => $va11->TOTCOPAY,
                        'USE_STATUS'     => $va11->USE_STATUS,
                        'TOTAL'          => $va11->TOTAL,  
                        'SIGCODE'        => $va11->SIGCODE,                      
                        'SIGTEXT'        => $va11->SIGTEXT,
                        'PROVIDER'        => $va11->PROVIDER,  
                        'user_id'        => $iduser
                    ]);
                }
                //D_pat
                $data_pat_ = DB::connection('mysql2')->select('
                    SELECT v.hcode HCODE
                        ,v.hn HN
                        ,pt.chwpart CHANGWAT
                        ,pt.amppart AMPHUR
                        ,DATE_FORMAT(pt.birthday,"%Y%m%d") DOB
                        ,pt.sex SEX
                        ,pt.marrystatus MARRIAGE 
                        ,pt.occupation OCCUPA
                        ,lpad(pt.nationality,3,0) NATION
                        ,pt.cid PERSON_ID
                        ,concat(pt.fname," ",pt.lname,",",pt.pname) NAMEPAT
                        ,pt.pname TITLE
                        ,pt.fname FNAME 
                        ,pt.lname LNAME
                        ,"1" IDTYPE
                        from vn_stat v
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN ipt i on i.vn = v.vn 
                        LEFT JOIN patient pt on pt.hn = v.hn 
                        WHERE v.vn IN("'.$va1->vn.'")
                ');
                foreach ($data_pat_ as $va14) {
                    D_pat::insert([
                        'HCODE'              => $va14->HCODE,
                        'HN'                 => $va14->HN,
                        'CHANGWAT'           => $va14->CHANGWAT,
                        'AMPHUR'             => $va14->AMPHUR,
                        'DOB'                => $va14->DOB,
                        'SEX'                => $va14->SEX,
                        'MARRIAGE'           => $va14->MARRIAGE,
                        'OCCUPA'             => $va14->OCCUPA,
                        'NATION'             => $va14->NATION,
                        'PERSON_ID'          => $va14->PERSON_ID,
                        'NAMEPAT'            => $va14->NAMEPAT,
                        'TITLE'              => $va14->TITLE,
                        'FNAME'              => $va14->FNAME,
                        'LNAME'              => $va14->LNAME,
                        'IDTYPE'             => $va14->IDTYPE,
                        'user_id'            => $iduser,
                    ]);
                }
                 //D_cht
                 $data_cht_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                    ,v.an AN
                    ,DATE_FORMAT(if(a.an is null,v.vstdate,a.dchdate),"%Y%m%d") DATE
                    ,round(if(a.an is null,vv.income,a.income),2) TOTAL
                    ,round(if(a.an is null,vv.paid_money,a.paid_money),2) PAID
                    ,if(vv.paid_money >"0" or a.paid_money >"0","10",pt.pcode) PTTYPE
                    ,pp.cid PERSON_ID 
                    ,v.vn SEQ
                    from ovst v
                    LEFT JOIN vn_stat vv on vv.vn = v.vn
                    LEFT JOIN an_stat a on a.an = v.an
                    LEFT JOIN patient pp on pp.hn = v.hn
                    LEFT JOIN pttype pt on pt.pttype = vv.pttype or pt.pttype=a.pttype
                    LEFT JOIN pttype p on p.pttype = a.pttype 
                    WHERE v.vn IN("'.$va1->vn.'")  
                    
                ');
                foreach ($data_cht_ as $va15) {
                    D_cht::insert([
                        'HN'                => $va15->HN,
                        'AN'                => $va15->AN,
                        'DATE'              => $va15->DATE,
                        'TOTAL'             => $va15->TOTAL,
                        'PAID'              => $va15->PAID,
                        'PTTYPE'            => $va15->PTTYPE,
                        'PERSON_ID'         => $va15->PERSON_ID,
                        'SEQ'               => $va15->SEQ,
                        'user_id'           => $iduser,
                    ]);
                }
                 //D_cha
                 $data_cha_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                        ,if(v1.an is null,"",v1.an) AN 
                        ,if(v1.an is null,DATE_FORMAT(v.vstdate,"%Y%m%d"),DATE_FORMAT(v1.dchdate,"%Y%m%d")) DATE
                        ,if(v.paidst in("01","03"),dx.chrgitem_code2,dc.chrgitem_code1) CHRGITEM
                        ,round(sum(v.sum_price),2) AMOUNT
                        ,p.cid PERSON_ID 
                        ,ifnull(v.vn,v.an) SEQ
                        from opitemrece v
                        LEFT JOIN vn_stat vv on vv.vn = v.vn
                        LEFT JOIN patient p on p.hn = v.hn
                        LEFT JOIN ipt v1 on v1.an = v.an
                        LEFT JOIN income i on v.income=i.income
                        LEFT JOIN drg_chrgitem dc on i.drg_chrgitem_id=dc.drg_chrgitem_id 
                        LEFT JOIN drg_chrgitem dx on i.drg_chrgitem_id= dx.drg_chrgitem_id  
                        WHERE vv.vn IN("'.$va1->vn.'") 
                        group by v.vn,CHRGITEM
                        union all
                        SELECT v.hn HN
                        ,v1.an AN 
                        ,if(v1.an is null,DATE_FORMAT(v.vstdate,"%Y%m%d"),DATE_FORMAT(v1.dchdate,"%Y%m%d")) DATE
                        ,if(v.paidst in("01","03"),dx.chrgitem_code2,dc.chrgitem_code1) CHRGITEM
                        ,round(sum(v.sum_price),2) AMOUNT
                        ,p.cid PERSON_ID 
                        ,ifnull(v.vn,v.an) SEQ
                        from opitemrece v
                        LEFT JOIN vn_stat vv on vv.vn = v.vn
                        LEFT JOIN patient p on p.hn = v.hn
                        LEFT JOIN ipt v1 on v1.an = v.an
                        LEFT JOIN income i on v.income=i.income
                        LEFT JOIN drg_chrgitem dc on i.drg_chrgitem_id=dc.drg_chrgitem_id 
                        LEFT JOIN drg_chrgitem dx on i.drg_chrgitem_id= dx.drg_chrgitem_id 
                        WHERE v1.vn IN("'.$va1->vn.'")  
                        group by v.an,CHRGITEM; 
                ');
                foreach ($data_cha_ as $va16) {
                    D_cha::insert([
                        'HN'                => $va16->HN,
                        'AN'                => $va16->AN,
                        'DATE'              => $va16->DATE,
                        'CHRGITEM'          => $va16->CHRGITEM,
                        'AMOUNT'            => $va16->AMOUNT, 
                        'PERSON_ID'         => $va16->PERSON_ID,
                        'SEQ'               => $va16->SEQ, 
                        'user_id'           => $iduser,
                    ]);
                }
                //D_ins
                $data_ins_ = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                    ,if(i.an is null,p.hipdata_code,pp.hipdata_code) INSCL
                    ,if(i.an is null,p.pcode,pp.pcode) SUBTYPE
                    ,v.cid CID
                    ,DATE_FORMAT(if(i.an is null,v.pttype_begin,ap.begin_date), "%Y%m%d")  DATEIN
                    ,DATE_FORMAT(if(i.an is null,v.pttype_expire,ap.expire_date), "%Y%m%d")   DATEEXP
                    ,if(i.an is null,v.hospmain,ap.hospmain) HOSPMAIN
                    ,if(i.an is null,v.hospsub,ap.hospsub) HOSPSUB
                    ,"" GOVCODE
                    ,"" GOVNAME
                    ,ifnull(if(i.an is null,vp.claim_code or vp.auth_code,ap.claim_code),r.sss_approval_code) PERMITNO
                    ,"" DOCNO
                    ,"" OWNRPID 
                    ,"" OWNRNAME
                    ,i.an AN
                    ,v.vn SEQ
                    ,"" SUBINSCL 
                    ,"" RELINSCL
                    ,"" HTYPE
                    from vn_stat v
                    LEFT JOIN pttype p on p.pttype = v.pttype
                    LEFT JOIN ipt i on i.vn = v.vn 
                    LEFT JOIN pttype pp on pp.pttype = i.pttype
                    left join ipt_pttype ap on ap.an = i.an
                    left join visit_pttype vp on vp.vn = v.vn
                    LEFT JOIN rcpt_debt r on r.vn = v.vn
                    left join patient px on px.hn = v.hn 
                    WHERE v.vn IN("'.$va1->vn.'")   
                ');
                foreach ($data_ins_ as $va17) {
                    D_ins::insert([
                        'HN'                => $va17->HN,
                        'INSCL'             => $va17->INSCL,
                        'SUBTYPE'           => $va17->SUBTYPE,
                        'CID'               => $va17->CID,
                        'DATEIN'            => $va17->DATEIN, 
                        'DATEEXP'           => $va17->DATEEXP,
                        'HOSPMAIN'          => $va17->HOSPMAIN, 
                        'HOSPSUB'           => $va17->HOSPSUB,
                        'GOVCODE'           => $va17->GOVCODE,
                        'GOVNAME'           => $va17->GOVNAME,
                        'PERMITNO'          => $va17->PERMITNO,
                        'DOCNO'             => $va17->DOCNO,
                        'OWNRPID'           => $va17->OWNRPID,
                        'OWNRNAME'          => $va17->OWNRNAME,
                        'AN'                => $va17->AN,
                        'SEQ'               => $va17->SEQ,
                        'SUBINSCL'          => $va17->SUBINSCL,
                        'RELINSCL'          => $va17->RELINSCL,
                        'HTYPE'             => $va17->HTYPE,
                        'user_id'           => $iduser,
                    ]);
                }
         }
         
         d_adp::where('CODE','=','XXXXXX')->delete();
       
        return back();
    }

            // $s_con_icode_pang = "SELECT pang_icode FROM pang_icode WHERE pang_id='$pang' LIMIT 1000";
            // $q_con_icode_pang = mysqli_query($con_money, $s_con_icode_pang) or die(nl2br($s_con_icode_pang)."|s_con_icode_pang|pang_opd_sql|");
            // $concat_pang_icode = "";
            // while($r_con_icode_pang=mysqli_fetch_array($q_con_icode_pang)){
            //   $concat_pang_icode.="'".$r_con_icode_pang['pang_icode']."',";
    
   
 }