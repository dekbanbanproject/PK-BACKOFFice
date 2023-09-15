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
                D_opd::where('d_anaconda_id','=','1')->delete();
                D_oop::where('d_anaconda_id','=','1')->delete();
                D_orf::where('d_anaconda_id','=','1')->delete();
                D_odx::where('d_anaconda_id','=','1')->delete();
                // D_dru::where('user_id','=',$iduser)->delete();
                D_idx::where('d_anaconda_id','=','1')->delete();
                D_ipd::where('d_anaconda_id','=','1')->delete();
                D_irf::where('d_anaconda_id','=','1')->delete();

                D_aer::where('d_anaconda_id','=','1')->delete();
                D_iop::where('d_anaconda_id','=','1')->delete();
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
                $data_opd = DB::connection('mysql2')->select('
                        SELECT  v.hn HN
                        ,v.spclty CLINIC
                        ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                        ,concat(substr(o.vsttime,1,2),substr(o.vsttime,4,2)) TIMEOPD
                        ,v.vn SEQ
                        ,"1" UUC 
                        from vn_stat v
                        LEFT JOIN ovst o on o.vn = v.vn
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN patient pt on pt.hn = v.hn
                        LEFT JOIN ipt i on i.vn = v.vn                        
                        LEFT JOIN opitemrece op on op.an = i.an
                        WHERE i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND p.hipdata_code ="ucs"
                        AND op.icode = "3010887" 
                        GROUP BY i.an                   
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
                    $addo->d_anaconda_id  = 1;
                    $addo->save();
                }

                //D_orf
                $data_orf = DB::connection('mysql2')->select('
                        SELECT v.hn HN
                        ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                        ,v.spclty CLINIC
                        ,ifnull(r1.refer_hospcode,r2.refer_hospcode) REFER
                        ,"0100" REFERTYPE
                        ,v.vn SEQ 
                        from vn_stat v
                        LEFT JOIN ovst o on o.vn = v.vn
                        LEFT JOIN referin r1 on r1.vn = v.vn
                        LEFT JOIN referout r2 on r2.vn = v.vn
                        LEFT JOIN ipt i on i.vn = v.vn
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN opitemrece op on op.an = i.an
                        WHERE i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"                 
                        and (r1.vn is not null or r2.vn is not null)
                        AND p.hipdata_code ="ucs"
                        AND op.icode = "3010887" ;
                '); 
                foreach ($data_orf as $va4) {              
                    $addof = new D_orf;  
                    $addof->HN             = $va4->HN;
                    $addof->CLINIC         = $va4->CLINIC;
                    $addof->DATEOPD         = $va4->DATEOPD;
                    $addof->REFER          = $va4->REFER;
                    $addof->SEQ            = $va4->SEQ;
                    $addof->REFERTYPE      = $va4->REFERTYPE; 
                    $addof->user_id        = $iduser;
                    $addof->d_anaconda_id   = 1;
                    $addof->save();
                }

                //D_oop
                $data_oop = DB::connection('mysql2')->select('
                        SELECT v.hn HN
                        ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                        ,v.spclty CLINIC
                        ,o.icd10 OPER
                        ,if(d.licenseno="","-99999",d.licenseno) DROPID
                        ,pt.cid PERSON_ID
                        ,v.vn SEQ 
                        from vn_stat v
                        LEFT JOIN ovstdiag o on o.vn = v.vn
                        LEFT JOIN patient pt on v.hn=pt.hn
                        LEFT JOIN doctor d on d.`code` = o.doctor
                        LEFT JOIN icd9cm1 ic on ic.code = o.icd10
                        LEFT JOIN ipt i on i.vn = v.vn
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN opitemrece op on op.an = i.an
                        WHERE i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND p.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                ');
                foreach ($data_oop as $va6) {
                    $addoop = new D_oop;  
                    $addoop->HN             = $va6->HN;
                    $addoop->CLINIC         = $va6->CLINIC;
                    $addoop->DATEOPD        = $va6->DATEOPD;
                    $addoop->OPER           = $va6->OPER;
                    $addoop->DROPID         = $va6->DROPID;
                    $addoop->PERSON_ID      = $va6->PERSON_ID; 
                    $addoop->SEQ            = $va6->SEQ; 
                    $addoop->user_id        = $iduser;
                    $addoop->d_anaconda_id   = 1;
                    $addoop->save(); 
                }

                // D_odx
                $data_odx = DB::connection('mysql2')->select('
                    SELECT v.hn HN
                        ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEDX
                        ,v.spclty CLINIC
                        ,o.icd10 DIAG
                        ,o.diagtype DXTYPE
                        ,if(d.licenseno="","-99999",d.licenseno) DRDX
                        ,v.cid PERSON_ID
                        ,v.vn SEQ 
                        from vn_stat v
                        LEFT JOIN ovstdiag o on o.vn = v.vn
                        LEFT JOIN doctor d on d.`code` = o.doctor
                        LEFT JOIN icd101 ic on ic.code = o.icd10
                        LEFT JOIN ipt i on i.vn = v.vn
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN opitemrece op on op.an = i.an
                        WHERE i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND p.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                ');
                foreach ($data_odx as $va5) {
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
                    $adddx->d_anaconda_id  = 1;
                    $adddx->save();                    
                }
                //d_idx
                $data_idx = DB::connection('mysql2')->select('
                    SELECT a.an AN,o.icd10 DIAG
                        ,o.diagtype DXTYPE
                        ,if(d.licenseno="","-99999",d.licenseno) DRDX
                        FROM an_stat a
                        LEFT JOIN iptdiag o on o.an = a.an
                        LEFT JOIN doctor d on d.`code` = o.doctor
                        LEFT JOIN ipt ip on ip.an = a.an
                        LEFT JOIN pttype p on p.pttype = a.pttype
                        LEFT JOIN opitemrece op on op.an = ip.an
                        INNER JOIN icd101 i on i.code = o.icd10 
                        LEFT JOIN vn_stat x on x.vn = ip.vn
                        WHERE ip.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND p.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                ');
                foreach ($data_idx as $va7) {
                    $addidrx = new D_idx; 
                    $addidrx->AN             = $va7->AN;
                    $addidrx->DIAG           = $va7->DIAG;
                    $addidrx->DXTYPE         = $va7->DXTYPE;
                    $addidrx->DRDX           = $va7->DRDX; 
                    $addidrx->user_id        = $iduser;
                    $addidrx->d_anaconda_id  = 1;
                    $addidrx->save(); 
                }
                //D_ipd
                $data_ipd = DB::connection('mysql2')->select('
                    SELECT v.hn HN,v.an AN
                        ,DATE_FORMAT(i.regdate,"%Y%m%d") DATEADM
                        ,Time_format(i.regtime,"%H%i") TIMEADM
                        ,DATE_FORMAT(i.dchdate,"%Y%m%d") DATEDSC
                        ,Time_format(i.dchtime,"%H%i")  TIMEDSC
                        ,right(i.dchstts,1) DISCHS
                        ,right(i.dchtype,1) DISCHT
                        ,i.ward WARDDSC,i.spclty DEPT
                        ,format(i.bw/1000,3) ADM_W
                        ,"1" UUC ,"I" SVCTYPE 
                        FROM an_stat v
                        LEFT JOIN ipt i on i.an = v.an
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN patient pt on pt.hn = v.hn
                        LEFT JOIN vn_stat x on x.vn = i.vn
                        LEFT JOIN opitemrece op on op.an = i.an
                        WHERE i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND p.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                ');
                foreach ($data_ipd as $va10) {                
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
                    $addipd->d_anaconda_id  = 1;
                    $addipd->save();
                }
                //D_irf
                $data_irf = DB::connection('mysql2')->select('
                        SELECT ""d_irf_id,v.an AN
                        ,ifnull(o.refer_hospcode,oo.refer_hospcode) REFER
                        ,"0100" REFERTYPE,"" created_at,"" updated_at
                        FROM an_stat v
                        LEFT JOIN referout o on o.vn =v.an
                        LEFT JOIN referin oo on oo.vn =v.an
                        LEFT JOIN ipt ip on ip.an = v.an
                        LEFT JOIN vn_stat x on x.vn = ip.vn
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN opitemrece op on op.an = ip.an
                        WHERE ip.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        and (v.an in(select vn from referin where vn = oo.vn) or v.an in(select vn from referout where vn = o.vn))
                        AND p.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                ');
                foreach ($data_irf as $va11) {
                    D_irf::insert([
                        'AN'                 => $va11->AN,
                        'REFER'              => $va11->REFER,
                        'REFERTYPE'          => $va11->REFERTYPE,
                        'user_id'            => $iduser,
                        'd_anaconda_id'      => 1
                    ]);
                }
                //D_aer
                $data_aer = DB::connection('mysql2')->select('
                        SELECT ""d_aer_id,v.hn HN,i.an AN
                        ,v.vstdate DATEOPD,vv.claim_code AUTHAE
                        ,"" AEDATE,"" AETIME,"" AETYPE,"" REFER_NO,"" REFMAINI
                        ,"" IREFTYPE,"" REFMAINO,"" OREFTYPE,"" UCAE,"" EMTYPE,v.vn SEQ
                        ,"" AESTATUS,"" DALERT,"" TALERT,"" created_at,"" updated_at
                        from vn_stat v
                        LEFT JOIN ipt i on i.vn = v.vn
                        LEFT JOIN visit_pttype vv on vv.vn = v.vn
                        LEFT OUTER JOIN pttype pt on pt.pttype =v.pttype
                        LEFT JOIN opitemrece op on op.an = i.an
                        WHERE i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND pt.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                        and i.an is null
                        GROUP BY v.vn

                        union all

                        SELECT ""d_aer_id,v.hn HN
                        ,v.an AN,v.dchdate DATEOPD,vv.claim_code AUTHAE
                        ,"" AEDATE,"" AETIME,"" AETYPE,"" REFER_NO,"" REFMAINI
                        ,"" IREFTYPE,"" REFMAINO,"" OREFTYPE,"" UCAE,"" EMTYPE
                        ,"" SEQ,"" AESTATUS,"" DALERT,"" TALERT,"" created_at,"" updated_at
                        from an_stat v
                        LEFT JOIN ipt_pttype vv on vv.an = v.an
                        LEFT OUTER JOIN pttype pt on pt.pttype =v.pttype
                        LEFT JOIN opitemrece op on op.an = v.an
                        WHERE v.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND pt.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                        group by v.an;
                ');

                foreach ($data_aer as $va12) {
                    D_aer::insert([
                        'HN'                => $va12->HN,
                        'AN'                => $va12->AN,
                        'DATEOPD'           => $va12->DATEOPD,
                        'AUTHAE'            => $va12->AUTHAE,
                        'AEDATE'            => $va12->AEDATE,
                        'AETIME'            => $va12->AETIME,
                        'AETYPE'            => $va12->AETYPE,
                        'REFER_NO'          => $va12->REFER_NO,
                        'REFMAINI'          => $va12->REFMAINI,
                        'IREFTYPE'          => $va12->IREFTYPE,
                        'REFMAINO'          => $va12->REFMAINO,
                        'OREFTYPE'          => $va12->OREFTYPE,
                        'UCAE'              => $va12->UCAE,
                        'SEQ'               => $va12->SEQ,
                        'AESTATUS'          => $va12->AESTATUS,
                        'DALERT'            => $va12->DALERT,
                        'TALERT'            => $va12->TALERT,
                        'user_id'           => $iduser,
                        'd_anaconda_id'     => 1
                    ]);
                }

                //D_iop
                $data_iop = DB::connection('mysql3')->select('
                        SELECT "" d_iop_id,v.an AN
                        ,o.icd9 OPER
                        ,o.oper_type as OPTYPE
                        ,if(d.licenseno="","-99999",d.licenseno) DROPID
                        ,DATE_FORMAT(o.opdate,"%Y%m%d") DATEIN
                        ,Time_format(o.optime,"%H%i") TIMEIN
                        ,DATE_FORMAT(o.enddate,"%Y%m%d") DATEOUT
                        ,Time_format(o.endtime,"%H%i") TIMEOUT,"" created_at,"" updated_at
                        FROM an_stat v
                        LEFT JOIN iptoprt o on o.an = v.an
                        LEFT JOIN doctor d on d.`code` = o.doctor
                        INNER JOIN icd9cm1 i on i.code = o.icd9
                        LEFT JOIN ipt ip on ip.an = v.an
                        LEFT OUTER JOIN pttype pt on pt.pttype =v.pttype
                        LEFT JOIN opitemrece op on op.an = v.an
                        WHERE v.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND pt.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                         
                ');
                foreach ($data_iop as $va7) {
                    D_iop::insert([
                        'AN'                => $va7->AN,
                        'OPER'              => $va7->OPER,
                        'OPTYPE'            => $va7->OPTYPE,
                        'DROPID'            => $va7->DROPID,
                        'DATEIN'            => $va7->DATEIN,
                        'TIMEIN'            => $va7->TIMEIN,
                        'DATEOUT'           => $va7->DATEOUT,
                        'TIMEOUT'           => $va7->TIMEOUT,
                        'user_id'           => $iduser,
                        'd_anaconda_id'     => 1
                    ]);
                }
                // D_pat
                $data_pat = DB::connection('mysql3')->select('
                        SELECT "" d_pat_id
                        ,v.hcode HCODE
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
                        ,"" created_at
                        ,"" updated_at
                        from vn_stat v
                        LEFT JOIN pttype p on p.pttype = v.pttype
                        LEFT JOIN ipt i on i.vn = v.vn
                        LEFT JOIN patient pt on pt.hn = v.hn
                        LEFT JOIN opitemrece op on op.an = i.an
                        WHERE i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                        AND p.hipdata_code ="ucs"
                        AND op.icode = "3010887"
                         
                 
                ');
                foreach ($data_pat as $va2) {
                    D_pat::insert([
                        'HCODE'               => $va2->HCODE,
                        'HN'                  => $va2->HN,
                        'CHANGWAT'            => $va2->CHANGWAT,
                        'AMPHUR'              => $va2->AMPHUR,
                        'DOB'                 => $va2->DOB,
                        'SEX'                 => $va2->SEX,
                        'MARRIAGE'            => $va2->MARRIAGE,
                        'OCCUPA'              => $va2->OCCUPA,
                        'NATION'              => $va2->NATION,
                        'PERSON_ID'           => $va2->PERSON_ID,
                        'NAMEPAT'             => $va2->NAMEPAT,
                        'TITLE'               => $va2->TITLE,
                        'FNAME'               => $va2->FNAME,
                        'LNAME'               => $va2->LNAME,
                        'IDTYPE'              => $va2->IDTYPE,
                        'user_id'             => $iduser,
                        'd_anaconda_id'     => 1
                    ]);
                }
                //D-dru
                // $data_dru = DB::connection('mysql3')->select('
                //     SELECT vv.hcode HCODE
                //     ,v.hn HN
                //     ,v.an AN
                //     ,vv.spclty CLINIC
                //     ,vv.cid PERSON_ID
                //     ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATE_SERV
                //     ,d.icode DID
                //     ,concat(d.`name`," ",d.strength," ",d.units) DIDNAME
                //     ,sum(v.qty) AMOUNT
                //     ,round(v.unitprice,2) DRUGPRIC
                //     ,"0.00" DRUGCOST
                //     ,d.did DIDSTD
                //     ,d.units UNIT
                //     ,concat(d.packqty,"x",d.units) UNIT_PACK
                //     ,v.vn SEQ
                //     ,oo.presc_reason DRUGREMARK
                //     ,"" PA_NO
                //     ,"" TOTCOPAY
                //     ,if(v.item_type="H","2","1") USE_STATUS
                //     ,"" TOTAL,"" SIGCODE,""  SIGTEXT 
                //     from opitemrece v
                //     LEFT JOIN drugitems d on d.icode = v.icode
                //     LEFT JOIN vn_stat vv on vv.vn = v.vn
                //     LEFT JOIN ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode
                   
                //     where vv.vstdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                //     and d.did is not null
                //     GROUP BY v.vn,did

                //     UNION all

                //     SELECT pt.hcode HCODE
                //     ,v.hn HN
                //     ,v.an AN
                //     ,v1.spclty CLINIC
                //     ,pt.cid PERSON_ID
                //     ,DATE_FORMAT((v.vstdate),"%Y%m%d") DATE_SERV
                //     ,d.icode DID
                //     ,concat(d.`name`," ",d.strength," ",d.units) DIDNAME
                //     ,sum(v.qty) AMOUNT
                //     ,round(v.unitprice,2) DRUGPRIC
                //     ,"0.00" DRUGCOST
                //     ,d.did DIDSTD
                //     ,d.units UNIT
                //     ,concat(d.packqty,"x",d.units) UNIT_PACK
                //     ,ifnull(v.vn,v.an) SEQ
                //     ,oo.presc_reason DRUGREMARK
                //     ,"" PA_NO
                //     ,"" TOTCOPAY
                //     ,if(v.item_type="H","2","1") USE_STATUS
                //     ,"" TOTAL,"" SIGCODE,""  SIGTEXT 
                //     from opitemrece v
                //     LEFT JOIN drugitems d on d.icode = v.icode
                //     LEFT JOIN patient pt  on v.hn = pt.hn
                //     inner JOIN ipt v1 on v1.an = v.an
                //     LEFT JOIN ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode
                    
                //     where v.vstdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                //     and d.did is not null AND v.qty <> "0"
                //     GROUP BY v.an,d.icode,USE_STATUS;
                // ');
                // foreach ($data_dru as $va9) { 
                //     $adddrx = new D_dru;  
                //     $adddrx->HN             = $va9->HN;
                //     $adddrx->CLINIC         = $va9->CLINIC;
                //     $adddrx->HCODE          = $va9->HCODE;
                //     $adddrx->AN             = $va9->AN;
                //     $adddrx->PERSON_ID      = $va9->PERSON_ID;
                //     $adddrx->DATE_SERV      = $va9->DATE_SERV;
                //     $adddrx->DID            = $va9->DID; 
                //     $adddrx->DIDNAME        = $va9->DIDNAME; 
                //     $adddrx->AMOUNT         = $va9->AMOUNT;
                //     $adddrx->DRUGPRIC       = $va9->DRUGPRIC;
                //     $adddrx->DRUGCOST       = $va9->DRUGCOST;
                //     $adddrx->DIDSTD         = $va9->DIDSTD;
                //     $adddrx->UNIT           = $va9->UNIT;
                //     $adddrx->UNIT_PACK      = $va9->UNIT_PACK;
                //     $adddrx->SEQ            = $va9->SEQ;
                //     $adddrx->DRUGREMARK     = $va9->DRUGREMARK;
                //     $adddrx->PA_NO          = $va9->PA_NO;
                //     $adddrx->TOTCOPAY       = $va9->TOTCOPAY;
                //     $adddrx->USE_STATUS     = $va9->USE_STATUS;
                //     $adddrx->TOTAL          = $va9->TOTAL;
                //     $adddrx->SIGCODE        = $va9->SIGCODE; 
                //     $adddrx->SIGTEXT        = $va9->SIGTEXT; 
                //     $adddrx->user_id        = $iduser;
                //     $adddrx->d_anaconda_id  = 1;
                //     $adddrx->save();
                // }


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