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
use App\Models\Acc_imc_hos;
use App\Models\Check_sit_auto;
use App\Models\Acc_imc_an;
use App\Models\Acc_ucep24;
use App\Models\Stm;

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


class ImcController extends Controller
 { 
    // ***************** ucep24********************************

    public function imc(Request $request)
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
                $data = DB::connection('mysql')->select('SELECT * from acc_imc_hos'); 
            } else {
                $iduser = Auth::user()->id;
                // D_ins::where('user_id','=',$iduser)->delete();
                // Tempexport::where('user_id','=',$iduser)->delete();
                // D_adp::where('user_id','=',$iduser)->delete(); 
                D_opd::where('user_id','=',$iduser)->delete();
                // D_oop::where('user_id','=',$iduser)->delete();
                // D_orf::where('user_id','=',$iduser)->delete();
                // D_odx::where('user_id','=',$iduser)->delete();
                // D_dru::where('user_id','=',$iduser)->delete();
                // D_idx::where('user_id','=',$iduser)->delete();
                // D_ipd::where('user_id','=',$iduser)->delete();
                // D_irf::where('user_id','=',$iduser)->delete();
                // $data_ = DB::connection('mysql2')->select('  
                //     SELECT a.an from hos.an_stat a
                //     LEFT JOIN hos.iptdiag i on i.an = a.an
                //     where a.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                //     and i.icd10 between "i60" and "i64"
                //     union
                //     SELECT a.an from hos.an_stat a
                //     LEFT JOIN hos.iptdiag i on i.an = a.an
                //     where a.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                //     and i.icd10 between "s061" and "s069"
                //     union
                //     SELECT a.an from hos.an_stat a
                //     LEFT JOIN hos.iptdiag i on i.an = a.an
                //     where a.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                //     and i.icd10 between "s140" and "s141"
                //     union
                //     SELECT a.an from hos.an_stat a
                //     LEFT JOIN hos.iptdiag i on i.an = a.an
                //     where a.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                //     and i.icd10 between "s240" and "s241"
                //     union
                //     SELECT a.an from hos.an_stat a
                //     LEFT JOIN hos.iptdiag i on i.an = a.an
                //     where a.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                //     and i.icd10 between "s340" and "s341"
                //     union
                //     SELECT a.an from hos.an_stat a
                //     LEFT JOIN hos.iptdiag i on i.an = a.an
                //     where a.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                //     and i.icd10 between "s343" and "s343"
                //     union
                //     SELECT a.an from hos.an_stat a
                //     LEFT JOIN hos.iptdiag i on i.an = a.an
                //     where a.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                //     and i.icd10 between "s720" and "s722";  
                // '); 
                // Acc_imc_an::truncate();
                // foreach ($data_ as $key => $value) {    
                //     Acc_imc_an::insert([ 
                //         'an'                => $value->an, 
                //     ]);
                // }
                $datamchos_ = DB::connection('mysql2')->select('
                    SELECT a.vn,a.hn,a.an,pa.cid,CONCAT(pa.pname,pa.fname," ",pa.lname) as ptname
                        ,GROUP_CONCAT(distinct d.icd10) as icd10
                        ,a.regdate,a.dchdate,a.pttype,a.income,a.uc_money,a.paid_money,a.income-a.paid_money as debit
                        from hos.an_stat a 
                        LEFT JOIN hos.pttype p on p.pttype = a.pttype
                        LEFT JOIN hos.patient pa on pa.hn = a.hn
                        LEFT JOIN hos.iptdiag d on d.an = a.an 
                        LEFT JOIN hos.ipt i1 on i1.an = a.an 
                        LEFT JOIN hos.opitemrece op on op.an = a.an
                        LEFT JOIN hos.nondrugitems n on n.icode = op.icode
                        where a.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                        and p.hipdata_code ="ucs"
                        AND op.icode = "3010887" 
                        GROUP BY a.an
                        ORDER BY a.hn;
                ');
                Acc_imc_hos::truncate();
                Acc_imc_an::truncate();
                foreach ($datamchos_ as $key => $value2) {
                    Acc_imc_hos::insert([
                        'vn'          => $value2->vn,
                        'hn'          => $value2->hn,
                        'an'          => $value2->an,
                        'cid'         => $value2->cid,
                        'ptname'      => $value2->ptname,
                        'icd10'       => $value2->icd10,
                        'regdate'     => $value2->regdate,
                        'dchdate'     => $value2->dchdate,
                        'pttype'      => $value2->pttype,
                        'income'      => $value2->income,
                        'paid_money'  => $value2->paid_money,
                        'debit'       => $value2->debit,
                    ]);

                    Acc_imc_an::insert([ 
                        'vn'                => $value2->vn, 
                        'an'                => $value2->an
                    ]);
                }

                //D_opd
                $data_opd = DB::connection('mysql3')->select('
                        SELECT  v.hn HN
                        ,v.spclty CLINIC
                        ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                        ,concat(substr(o.vsttime,1,2),substr(o.vsttime,4,2)) TIMEOPD
                        ,v.vn SEQ
                        ,"1" UUC 
                        from vn_stat v
                        LEFT JOIN ovst o on o.vn = v.vn
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN ipt i on i.vn = v.vn
                        LEFT JOIN patient pt on pt.hn = v.hn
                        WHERE i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"                    
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


                $data = DB::connection('mysql')->select('SELECT * from acc_imc_hos where dchdate between "'.$startdate.'" AND "'.$enddate.'"');  
            }
                  
            return view('claim.imc',[
                'startdate'        =>     $startdate,
                'enddate'          =>     $enddate, 
                'data'             =>     $data, 
            ]);
    }

    // public function ucep24_an(Request $request,$an)
    // { 
    //         $startdate = $request->startdate;
    //         $enddate = $request->enddate;
     
    //         $date = date('Y-m-d');
    //         $y = date('Y') + 543;
    //         $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
    //         $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
    //         $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี
    //         $yearnew = date('Y');
    //         $yearold = date('Y')-1;
    //         $start = (''.$yearold.'-10-01');
    //         $end = (''.$yearnew.'-09-30'); 
 
    //             $data = DB::connection('mysql')->select('   
                       

    //                     select o.an,i.income,i.name as nameliss,sum(o.qty) as qty,
    //                     (select sum(sum_price) from hos.opitemrece where an=o.an and income = o.income and paidst in("02")) as paidst02,
    //                     (select sum(sum_price) from hos.opitemrece where an=o.an and income = o.income and paidst in("01","03")) as paidst0103,
    //                     (select sum(u.sum_price) from acc_ucep24 u where u.an= o.an and i.income = u.income) as paidst_ucep
    //                     from hos.opitemrece o
    //                     left outer join hos.nondrugitems n on n.icode = o.icode
    //                     left outer join hos.income i on i.income = o.income
    //                     where o.an = "'.$an.'"  
    //                     group by i.name
    //                     order by i.income
                      
    //             '); 

    //             // SELECT o.an,o.hn,pt.cid,concat(pt.pname,pt.fname," ",pt.lname) ptname
    //             // ,i.dchdate,ii.pttype
    //             // ,o.icode,n.name as nameliss,a.vstdate,o.rxdate,a.vsttime,o.rxtime,o.income,o.qty,o.unitprice,o.sum_price
    //             // ,hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate,"",o.rxtime))) ssz
    //             // from hos.opitemrece o
    //             // LEFT JOIN hos.ipt i on i.an = o.an
    //             // LEFT JOIN hos.ovst a on a.an = o.an
    //             // left JOIN hos.er_regist e on e.vn = i.vn
    //             // LEFT JOIN hos.ipt_pttype ii on ii.an = i.an
    //             // LEFT JOIN hos.pttype p on p.pttype = ii.pttype 
    //             // LEFT JOIN hos.s_drugitems n on n.icode = o.icode
    //             // LEFT JOIN hos.patient pt on pt.hn = a.hn
    //             // LEFT JOIN hos.pttype ptt on a.pttype = ptt.pttype	
                
    //             // WHERE i.an = "'.$an.'"  
    //             // and o.paidst ="02"
    //             // and p.hipdata_code ="ucs"
    //             // and DATEDIFF(o.rxdate,a.vstdate)<="1"
    //             // and hour(TIMEDIFF(concat(a.vstdate," ",a.vsttime),concat(o.rxdate," ",o.rxtime))) <="24"
    //             // and e.er_emergency_type  in("1","5")
    //             // and n.nhso_adp_code in(SELECT code from hshooterdb.h_ucep24)
    //             // select i.income,i.name,sum(o.qty),
    //             // (select sum(sum_price) from opitemrece where an=o.an and income = o.income and paidst in('02')),
    //             // (select sum(sum_price) from opitemrece where an=o.an and income = o.income and paidst in('01','03')),
    //             // (select sum(u.sum_price) from eclaimdb80.ucep_an u where u.an= o.an and i.income = u.income)

    //             // from opitemrece o
    //             // left outer join nondrugitems n on n.icode = o.icode
    //             // left outer join income i on i.income = o.income
    //             // where o.an ='666666666' 
    //             // group by i.name
    //             // order by i.income
             

    //         return view('ucep.ucep24_an',[
    //             'startdate'        =>     $startdate,
    //             'enddate'          =>     $enddate, 
    //             'data'             =>     $data, 
    //         ]);
    // }
    // public function ucep24_income(Request $request,$an,$income)
    // { 
    //         $startdate = $request->startdate;
    //         $enddate = $request->enddate;
    //         // select *
    //         // from acc_ucep24                         
    //         // where an = "'.$an.'"  and income = "'.$income.'" 
    //             $data = DB::connection('mysql')->select('  
    //                     select o.income,ifnull(n.icode,d.icode) as icode,ifnull(n.billcode,n.nhso_adp_code) as nhso_adp_code,ifnull(n.name,d.name) as dname,sum(o.qty) as qty,sum(sum_price) as sum_price
    //                     ,(SELECT sum(qty) from pkbackoffice.acc_ucep24 where an = o.an and icode = o.icode) as qty_ucep 
    //                     ,(SELECT sum(sum_price) from pkbackoffice.acc_ucep24 where an = o.an and icode = o.icode) as price_ucep
    //                     from hos.opitemrece o
    //                     left outer join hos.nondrugitems n on n.icode = o.icode
    //                     left outer join hos.drugitems d on d.icode = o.icode
    //                     left outer join hos.income i on i.income = o.income
    //                     where o.an = "'.$an.'"
    //                     and o.income = "'.$income.'" 
    //                     group by o.icode
    //                     order by o.icode
    //             '); 

    //         return view('ucep.ucep24_income',[
    //             'startdate'        =>     $startdate,
    //             'enddate'          =>     $enddate, 
    //             'data'             =>     $data, 
    //             'an'               =>     $an, 
    //             'income'           =>     $income, 
    //         ]);
    // }
    
    
   
 }