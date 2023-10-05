<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\Ot_one;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
// use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OtExport;
// use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
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
use App\Models\Aipn_stm;
use App\Models\Status; 
use App\Models\Aipn_ipdx;
use App\Models\Aipn_ipop;   
use App\Models\Aipn_session;
use App\Models\Aipn_billitems;
use App\Models\Aipn_ipadt;
use App\Models\Check_sit;
use App\Models\Stm;
use App\Models\D_aipn_main;
use App\Models\D_claim;
use App\Models\D_aipadt;
use App\Models\D_aipdx;
use App\Models\D_aipop;
use App\Models\D_abillitems;
use App\Models\Ssop_dispensing;
use App\Models\Ssop_billtran;
use App\Models\Ssop_billitems;
use App\Models\Claim_ssop;
use App\Models\Claim_sixteen_dru;
use App\Models\claim_sixteen_adp;
use App\Models\Claim_sixteen_cha;  
use App\Models\Claim_sixteen_cht;
use App\Models\Claim_sixteen_oop;
use App\Models\Claim_sixteen_odx;
use App\Models\Claim_sixteen_orf;
use App\Models\Claim_sixteen_pat;
use App\Models\Claim_sixteen_ins;
use App\Models\Claim_temp_ssop;
use App\Models\Claim_sixteen_opd;
use Auth;
use ZipArchive;
use Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\If_;
use Stevebauman\Location\Facades\Location; 
use SoapClient; 
use SplFileObject;
use File;
use Illuminate\Filesystem\Filesystem;
 

class AipnController extends Controller
{
    public function aipn(Request $request)
    {
        $startdate = $request->datepicker;
        $enddate = $request->datepicker2;
        $date = date('Y-m-d');
        // $data_main_ = DB::connection('mysql2')->select(' 
        //         SELECT
        //         i.an,i.vn,a.hn,p.cid,i.regdate,i.regtime,i.dchdate,i.dchtime,i.pttype,concat(p.pname,p.fname," ",p.lname) ptname
        //         ,pt.hipdata_code,a.income,a.income-a.rcpt_money-a.discount_money as debit
        //         FROM ipt i
        //         LEFT OUTER JOIN patient p on p.hn=i.hn 
        //         LEFT OUTER JOIN an_stat a on a.an=i.an 
        //         LEFT OUTER JOIN pttype pt on pt.pttype=i.pttype 
        //         LEFT OUTER JOIN opitemrece op on op.an=i.an 
        //         LEFT OUTER JOIN s_drugitems d on d.icode=op.icode 
        //         WHERE i.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
        //         AND i.pttype IN("A7","s7","14")
        //         group by i.an   
        // ');  
        // $iduser = Auth::user()->id;
        // D_aipn_main::truncate(); 
        //     foreach ($data_main_ as $key => $value) {    
        //         D_aipn_main::insert([
        //             'vn'                => $value->vn,
        //             'hn'                => $value->hn,
        //             'an'                => $value->an, 
        //             'dchdate'           => $value->dchdate,
        //             'debit'             => $value->debit 
        //         ]);
        //     $check = D_claim::where('an',$value->an)->where('hipdata_code',$value->hipdata_code)->count();
        //     if ($check > 0) {
        //         # code...
        //     } else {
        //         D_claim::insert([
        //             'vn'                => $value->vn,
        //             'hn'                => $value->hn,
        //             'an'                => $value->an,
        //             'cid'               => $value->cid,
        //             'pttype'            => $value->pttype,
        //             'ptname'            => $value->ptname,
        //             'dchdate'           => $value->dchdate,
        //             'hipdata_code'      => $value->hipdata_code,
        //             // 'qty'               => $value->qty,
        //             'sum_price'         => $value->debit,
        //             'type'              => 'SSS',
        //             'nhso_adp_code'     => 'AIPN',
        //             'claimdate'         => $date, 
        //             'userid'            => $iduser, 
        //         ]);
        //     }                   
            
        // }  
        $data['d_aipn_main'] = DB::connection('mysql')->select('SELECT * FROM d_aipn_main'); 
        $data['d_aipadt'] = DB::connection('mysql')->select('SELECT * FROM d_aipadt'); 
        $data['d_aipdx'] = DB::connection('mysql')->select('SELECT * FROM d_aipdx');
        $data['d_aipop'] = DB::connection('mysql')->select('SELECT * FROM d_aipop');
        $data['d_abillitems'] = DB::connection('mysql')->select('SELECT * FROM d_abillitems');

        $data['d_adispensing'] = DB::connection('mysql')->select('SELECT * FROM d_adispensing');   
        $data['d_adispenseditems'] = DB::connection('mysql')->select('SELECT * FROM d_adispenseditems');  
        
        
        
        return view('aipn.aipn',$data,[
            'startdate'                => $startdate,
            'enddate'                  => $enddate,
           
        ]);
    }
    public function aipn_main(Request $request)
    {
        $startdate = $request->datepicker;
        $enddate = $request->datepicker2;
        $date = date('Y-m-d');
        $data_main_ = DB::connection('mysql2')->select(' 
                SELECT
                i.an,i.vn,a.hn,p.cid,i.regdate,i.regtime,i.dchdate,i.dchtime,i.pttype,concat(p.pname,p.fname," ",p.lname) ptname
                ,pt.hipdata_code,a.income,a.income-a.rcpt_money-a.discount_money as debit
                FROM ipt i
                LEFT OUTER JOIN patient p on p.hn=i.hn 
                LEFT OUTER JOIN an_stat a on a.an=i.an 
                LEFT OUTER JOIN pttype pt on pt.pttype=i.pttype 
                LEFT OUTER JOIN opitemrece op on op.an=i.an 
                LEFT OUTER JOIN s_drugitems d on d.icode=op.icode 
                WHERE i.dchdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                AND i.pttype IN("A7","s7","14")
                group by i.an   
        ');  
        $iduser = Auth::user()->id;
        D_aipn_main::truncate(); 
            foreach ($data_main_ as $key => $value) {    
                D_aipn_main::insert([
                    'vn'                => $value->vn,
                    'hn'                => $value->hn,
                    'an'                => $value->an, 
                    'dchdate'           => $value->dchdate,
                    'debit'             => $value->debit 
                ]);
            $check = D_claim::where('an',$value->an)->where('hipdata_code','AIPN')->count();
            if ($check > 0) {
                # code...
            } else {
                D_claim::insert([
                    'vn'                => $value->vn,
                    'hn'                => $value->hn,
                    'an'                => $value->an,
                    'cid'               => $value->cid,
                    'pttype'            => $value->pttype,
                    'ptname'            => $value->ptname,
                    'dchdate'           => $value->dchdate,
                    'hipdata_code'      => $value->hipdata_code,
                    // 'qty'               => $value->qty,
                    'sum_price'         => $value->debit,
                    'type'              => 'IPD',
                    'nhso_adp_code'     => 'AIPN',
                    'claimdate'         => $date, 
                    'userid'            => $iduser, 
                ]);
            }  
        }  
         
        return response()->json([
            'status'    => '200'
        ]);
    }

    public function aipn_process(Request $request)
    {  
        $data_aipn = DB::connection('mysql')->select('SELECT vn,an from d_aipn_main');
        $iduser = Auth::user()->id;
        D_aipadt::truncate();
        D_aipdx::truncate();
        D_aipop::truncate();
        D_abillitems::truncate();

        foreach ($data_aipn as $key => $va1) {
            //D_aipadt
            $aipn_data = DB::connection('mysql2')->select('   
                    SELECT
                    i.an,  
                    i.an as AN,i.hn as HN,"0" as IDTYPE 
                    ,pt.cid as PIDPAT
                    ,pt.pname as TITLE
                    ,concat(pt.fname," ",pt.lname) as NAMEPAT 
                    ,pt.birthday as DOB
                    ,a.sex as SEX
                    ,pt.marrystatus as MARRIAGE
                    ,pt.chwpart as CHANGWAT
                    ,pt.amppart as AMPHUR
                    ,pt.citizenship as NATION
                    ,"C" as AdmType
                    ,"O" as AdmSource
                    ,i.regdate as DTAdm_d
                    ,i.regtime as DTAdm_t
                    ,i.dchdate as DTDisch_d
                    ,i.dchtime as DTDisch_t 
                    ,"0" AS LeaveDay                
                    ,i.dchstts as DischStat
                    ,i.dchtype as DishType
                    ,"" as AdmWt
                    ,i.ward as DishWard
                    ,sp.nhso_code as Dept
                    ,ptt.hipdata_code maininscl
                    ,i.pttype
                    ,concat(i.pttype,":",ptt.name) pttypename 
                    ,"10702" HMAIN
                    ,"IP" as ServiceType
                    from ipt i
                    LEFT OUTER JOIN patient pt on pt.hn=i.hn
                    LEFT OUTER JOIN ptcardno pc on pc.hn=pt.hn and pc.cardtype="02"
                    LEFT OUTER JOIN an_stat a on a.an=i.an
                    LEFT OUTER JOIN spclty sp on sp.spclty=i.spclty
                    LEFT OUTER JOIN pttype ptt on ptt.pttype=i.pttype
                    LEFT OUTER JOIN pttype_eclaim ec on ec.code=ptt.pttype_eclaim_id 
                    LEFT OUTER JOIN opitemrece oo on oo.an=i.an
                    LEFT OUTER JOIN income inc on inc.income=oo.income
                    LEFT OUTER JOIN s_drugitems d on d.icode=oo.icode 
                    WHERE i.an IN("'.$va1->an.'")                   
                    AND ptt.pttype IN("A7","s7","14")
                    group by i.an 
    
            ');  
            foreach ($aipn_data as $key => $value) {
                D_aipadt::insert([
                    'AN'             => $value->AN,
                    'HN'             => $value->HN,
                    'IDTYPE'         => $value->IDTYPE,
                    'PIDPAT'         => $value->PIDPAT,
                    'TITLE'          => $value->TITLE,
                    'NAMEPAT'        => $value->NAMEPAT,
                    'DOB'            => $value->DOB,
                    'SEX'            => $value->SEX,
                    'MARRIAGE'       => $value->MARRIAGE,
                    'CHANGWAT'       => $value->CHANGWAT,
                    'AMPHUR'         => $value->AMPHUR,
                    'NATION'         => $value->NATION,
                    'AdmType'        => $value->AdmType,
                    'AdmSource'      => $value->AdmSource,
                    'DTAdm_d'        => $value->DTAdm_d,
                    'DTAdm_t'        => $value->DTAdm_t,
                    'DTDisch_d'      => $value->DTDisch_d,
                    'DTDisch_t'      => $value->DTDisch_t,
                    'LeaveDay'       => $value->LeaveDay,
                    'DischStat'      => $value->DischStat,
                    'DishType'       => $value->DishType,
                    'AdmWt'          => $value->AdmWt,
                    'DishWard'       => $value->DishWard,
                    'Dept'           => $value->Dept,
                    'HMAIN'          => $value->HMAIN,
                    'ServiceType'    => $value->ServiceType 
                ]);    
            }

            //D_abillitems
            $aipn_billitems = DB::connection('mysql3')->select('   
                    SELECT  i.an,
                    i.an as AN,"" as sequence                            
                    ,i.regdate as DTAdm_d
                    ,i.regtime as DTAdm_t
                    ,i.dchdate as ServDate
                    ,i.dchtime as ServTime 
                    ,case 
                    when oo.item_type="H" then "04"
                    else zero(inc.income) end BillGr 
                    
                    ,inc.income as BillGrCS 
                                                    
                    ,ifnull(case  
                    when inc.income in (02) then d.nhso_adp_code
                    when inc.income in (03,04) then dd.billcode
                    when inc.income in (06,07) then d.nhso_adp_code
                    else d.nhso_adp_code end,"") CSCode

                    ,ifnull(case  
                    when inc.income in (03,04) then dd.tmt_tmlt
                    when inc.income in (06,07) then dd.tmt_tmlt
                    else "" end,"") STDCode

                    ,ifnull(case                 
                    when inc.income in (03,04) then "TMT"
                    when inc.income in (06,07) then "TMLT"
                    else "" end,"") CodeSys

                    ,oo.icode as LCCode
                    ,concat_ws("",d.name,d.strength) Descript
                    ,sum(oo.qty) as QTY
                    ,oo.UnitPrice as pricehos
                    ,dd.UnitPrice as pricecat
                    ,sum(oo.sum_price) ChargeAmt_ 
                    ,dd.tmt_tmlt 
                    ,inc.income

                    ,case 
                    when oo.paidst in ("01","03") then "T"
                    else "D" end ClaimCat

                    ,"0" as ClaimUP
                    ,"0" as ClaimAmt
                    ,i.dchdate
                    ,i.dchtime
                    ,sum(if(oo.paidst="04",sum_price,0)) Discount    
                    from ipt i
                    left outer join opitemrece oo on oo.an=i.an
                    left outer join an_stat a on a.an=i.an
                    left outer join patient pt on i.hn=pt.hn
                    left outer join income inc on inc.income=oo.income
                
                    left outer join s_drugitems d on oo.icode=d.icode
                    left join claim.aipn_drugcat_labcat dd on dd.icode=oo.icode	
                    left join claim.aipn_labcat_sks ls on ls.lccode=oo.icode
                    left join claim.aipn_drugcat_sks dks on dks.hospdcode=oo.icode

                    WHERE i.an IN("'.$va1->an.'")                        
                    and oo.qty<>0
                    and oo.UnitPrice<>0  
                    and inc.income NOT IN ("02","22" )      
                    group by oo.icode
                    order by i.an desc
            ');                
            $i = 1;
            foreach ($aipn_billitems as $key => $val_bill) {             
                    // $codesys = $val_bill->BillGr;
                    $cs_ = $val_bill->BillGrCS;
                    $cs = $val_bill->CSCode;
                    // $billcs = $val_bill->BillGrCS; 

                    if ($cs_ == '03') {
                        $csys = $val_bill->CodeSys;
                    }elseif ($cs_ == '02') {
                        $csys = $val_bill->CodeSys; 
                    }elseif ($cs_ == '06') {
                        $csys = $val_bill->CodeSys; 
                    }elseif ($cs_ == '04') {
                    $csys = $val_bill->CodeSys; 
                    }elseif ($cs_ == '07') {
                        $csys = $val_bill->CodeSys; 
                    } else {
                        $csys = '';
                    }

                    if ($cs == 'XXXX') {
                        $cs_code = '';
                    }elseif ($cs == 'XXXXX') {
                        $cs_code = '';  
                    }elseif ($cs == 'XXXXXX') {
                        $cs_code = ''; 
                    // }elseif ($cs == '04') {
                    //     $cs_ = '';
                    } else {
                        $cs_code = $val_bill->CSCode;
                    }
                                        
                    D_abillitems::insert([                        
                        'AN'                => $val_bill->AN,
                        'sequence'          => $i++,
                        'ServDate'          => $val_bill->ServDate,
                        'ServTime'          => $val_bill->ServTime,
                        'BillGr'            => $val_bill->BillGr,
                        'BillGrCS'          => $cs_,
                        'CSCode'            => $cs_code,
                        'LCCode'            => $val_bill->LCCode,
                        'Descript'          => $val_bill->Descript,
                        'QTY'               => $val_bill->QTY,
                        'UnitPrice'         => $val_bill->pricehos,
                        'ChargeAmt'         => $val_bill->QTY * $val_bill->pricehos,
                        'ClaimSys'          => "SS",
                        'CodeSys'           => $csys,
                        'STDCode'           => $val_bill->STDCode,
                        'Discount'          => "0.0000",
                        'ProcedureSeq'      => "0",
                        'DiagnosisSeq'      => "0",
                        'DateRev'           => $val_bill->ServDate,
                        'ClaimCat'          => $val_bill->ClaimCat,
                        'ClaimUP'           => $val_bill->ClaimUP,
                        'ClaimAmt'          => $val_bill->ClaimAmt 
                    ]); 
            
            
            }

            //D_aipop
            $aipn_ipop = DB::connection('mysql3')->select('   
                SELECT
                    i.an as AN,"" as sequence,"ICD9CM" as CodeSys 
                    ,cc.icd9 as Code,icdname(cc.icd9) as Procterm,doctorlicense(cc.doctor) as DR                        
                    ,date_format(if(opdate is null,caldatetime(regdate,regtime),caldatetime(opdate,optime)),"%Y-%m-%dT%T") as DateIn
                    ,date_format(if(enddate is null,caldatetime(regdate,regtime),caldatetime(enddate,endtime)),"%Y-%m-%dT%T") as DateOut
                    ," " as Location
                    from ipt i
                    join iptoprt cc on cc.an=i.an
                    WHERE i.an IN("'.$va1->an.'")  
                    group by cc.icd9
            ');
            $i = 1; 
            foreach ($aipn_ipop as $key => $ipop) {  
                $doctop = $ipop->DR;
                #ตัดขีด,  ออก
                   $pattern_drop = '/-/i';
                   $dr_cutop = preg_replace($pattern_drop, '', $doctop);
                   if ($dr_cutop == '') {
                    $doctop_ = 'ว47998';
                   } else {
                    $doctop_ = $dr_cutop;
                   } 
                   D_aipop::insert([
                    'an'             => $ipop->AN,
                    'sequence'       => $i++,
                    'CodeSys'        => $ipop->CodeSys,
                    'Code'           => $ipop->Code,
                    'Procterm'       => $ipop->Procterm,
                    'DR'             => $doctop_,
                    'DateIn'         => $ipop->DateIn,
                    'DateOut'        => $ipop->DateOut,
                    'Location'       => $ipop->Location 
                ]);
            }

            $aipn_ipdx = DB::connection('mysql3')->select('   
                SELECT 
                    i.an as AN
                    ,"" as sequence
                    ,diagtype as DxType
                    ,if(ifnull(aa.codeset,"")="TT","ICD-10-TM","ICD-10") as CodeSys
                    ,dx.icd10 as Dcode
                    ,icdname(dx.icd10) as DiagTerm 
                    ,doctorlicense(cc.doctor) as DR  
                    ,null datediag
                    from ipt i
                    join iptdiag dx on dx.an=i.an
                    join iptoprt cc on cc.an=i.an
                    left join icd101 aa on aa.code=dx.icd10
                    WHERE i.an IN("'.$va1->an.'")  
                    group by dx.icd10
                    order by diagtype,ipt_diag_id 
            ');
            $j = 1;  
            foreach ($aipn_ipdx as $key => $val_ipdx) { 
                $doct = $val_ipdx->DR;
                 #ตัดขีด,  ออก
                    $pattern_dr = '/-/i';
                    $dr_cut = preg_replace($pattern_dr, '', $doct);

                    if ($dr_cut == '') {
                        $doctop_s = 'ว47998';
                       } else {
                        $doctop_s = $dr_cut;
                       }
                
                    D_aipdx::insert([
                    'an'             => $val_ipdx->AN,
                    'sequence'       => $j++,
                    'DxType'         => $val_ipdx->DxType,
                    'CodeSys'        => $val_ipdx->CodeSys,
                    'Dcode'          => $val_ipdx->Dcode,
                    'DiagTerm'       => $val_ipdx->DiagTerm,
                    'DR'             => $doctop_s,
                    'datediag'       => $val_ipdx->datediag
                ]);
            }


            $update_billitems = DB::connection('mysql')->select('SELECT * FROM d_abillitems WHERE CodeSys ="TMLT" AND STDCode ="" OR ClaimCat="T" ');
            foreach ($update_billitems as $key => $valbil) {
                $id = $valbil->d_abillitems_id;
                $del = D_abillitems::find($id);
                $del->delete();            
            }
 
            $update_billitems2 = DB::connection('mysql')->select('SELECT * FROM d_abillitems WHERE CodeSys ="TMT" AND STDCode ="" OR ClaimCat="T" ');
            foreach ($update_billitems2 as $key => $valbil2) {
                $id = $valbil2->d_abillitems_id;
                $del = D_abillitems::find($id);
                $del->delete();            
            }





        }
       
         
        return response()->json([
            'status'    => '200'
        ]);
    }

    public function aipn_billitems_destroy(Request $request,$id)
    {
        $del = D_abillitems::find($id);
        $del->delete();
        return redirect()->route('claim.aipn'); 
        // return response()->json(['status' => '200']);
    }
   
}
   