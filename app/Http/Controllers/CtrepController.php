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
use Illuminate\Support\Facades\File;
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
use App\Models\Products_vendor;
use App\Models\Status; 
use App\Models\Products_request;
use App\Models\Products_request_sub;   
use App\Models\Leave_leader;
use App\Models\Leave_leader_sub;
use App\Models\Book_type;
use App\Models\Book_import_fam;
use App\Models\Book_signature;
use App\Models\Bookrep;
use App\Models\Book_objective;

use App\Models\D_apiofc_ins;
use App\Models\D_apiofc_iop;
use App\Models\D_apiofc_adp;
use App\Models\D_apiofc_aer;
use App\Models\D_apiofc_cha;
use App\Models\D_apiofc_cht;
use App\Models\D_apiofc_dru;
use App\Models\D_apiofc_idx;  
use App\Models\D_apiofc_pat;
use App\Models\D_apiofc_ipd;
use App\Models\D_apiofc_irf;
use App\Models\D_apiofc_ldv;
use App\Models\D_apiofc_odx;
use App\Models\D_apiofc_oop;
use App\Models\D_apiofc_opd;
use App\Models\D_apiofc_orf;
use App\Models\Book_send_person;
use App\Models\Book_sendteam;
use App\Models\Bookrepdelete;

use App\Models\D_ins;
use App\Models\D_pat;
use App\Models\D_opd;
use App\Models\D_orf;
use App\Models\D_odx;
use App\Models\D_cht;
use App\Models\D_cha;
use App\Models\D_oop;
use App\Models\D_claim;
use App\Models\D_adp;
use App\Models\D_dru;
use App\Models\D_idx;
use App\Models\D_iop;
use App\Models\D_ipd;
use App\Models\D_aer;
use App\Models\D_irf;
use App\Models\D_ofc_401;
use App\Models\D_ucep24_main;
use App\Models\A_stm_ct;
use App\Models\A_stm_ct_excel;
use Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http; 
use SoapClient;
use Arr; 
use App\Imports\ImportAcc_stm_ti;
use App\Imports\ImportAcc_stm_tiexcel_import;
use App\Imports\ImportAcc_stm_ofcexcel_import;
use App\Imports\ImportAcc_stm_lgoexcel_import;
use App\Models\D_ofc_repexcel;
use App\Models\D_ofc_rep;
use SplFileObject;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use ZipArchive;  
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\If_;
use Stevebauman\Location\Facades\Location; 
use Illuminate\Filesystem\Filesystem;

use Mail;
use Illuminate\Support\Facades\Storage;
  
 
date_default_timezone_set("Asia/Bangkok");

class CtrepController extends Controller
{  
    public function ct_rep(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate;
 
        $date = date('Y-m-d');
        $y = date('Y') + 543;
        $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
        $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
        $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี
        $yearnew = date('Y')+1;
        $yearold = date('Y');
        $start = (''.$yearold.'-10-01');
        $end = (''.$yearnew.'-09-30'); 
        if ($startdate == '') {  
            
        } else {
                $iduser = Auth::user()->id;
                D_ofc_401::truncate(); 
                $data_main_ = DB::connection('mysql2')->select(' 
                        SELECT v.vn,o.an,v.cid,v.hn,concat(pt.pname,pt.fname," ",pt.lname) ptname
                        ,v.vstdate,v.pttype  ,rd.sss_approval_code AS "Apphos",v.inc04 as xray
                        ,rd.amount AS price_ofc,v.income,ptt.hipdata_code 
                        ,group_concat(distinct hh.appr_code,":",hh.transaction_amount,"/") AS AppKTB 
                        ,GROUP_CONCAT(DISTINCT ov.icd10 order by ov.diagtype) AS icd10 
                        FROM hos.vn_stat v
                        LEFT OUTER JOIN hos.patient pt ON v.hn=pt.hn
                        LEFT OUTER JOIN hos.ovstdiag ov ON v.vn=ov.vn
                        LEFT OUTER JOIN hos.ovst o ON v.vn=o.vn
                        LEFT OUTER JOIN hos.opdscreen op ON v.vn = op.vn
                        LEFT OUTER JOIN hos.pttype ptt ON v.pttype=ptt.pttype 
                        LEFT OUTER JOIN hos.rcpt_debt rd ON v.vn=rd.vn
                        LEFT OUTER JOIN hos.hpc11_ktb_approval hh on hh.pid = pt.cid and hh.transaction_date = v.vstdate 
                        LEFT OUTER JOIN hos.ipt i on i.vn = v.vn
                        
                        WHERE o.vstdate BETWEEN "'.$startdate.'" and "'.$enddate.'"
                        AND v.pttype in ("O1","O2","O3","O4","O5") AND rd.sss_approval_code <> ""
                        AND v.pttype not in ("OF","FO") 
                        
                        AND o.an is null
                        AND v.pdx <> ""
                        GROUP BY v.vn; 
                ');                 
                foreach ($data_main_ as $key => $value) {    
                    D_ofc_401::insert([
                            'vn'                 => $value->vn,
                            'hn'                 => $value->hn,
                            'an'                 => $value->an, 
                            'pttype'             => $value->pttype,
                            'vstdate'            => $value->vstdate,
                            'Apphos'             => $value->Apphos,
                            'Appktb'             => $value->AppKTB,
                            'price_ofc'          => $value->price_ofc, 
                        ]);
                    $check = D_claim::where('vn',$value->vn)->count();
                    if ($check > 0) {
                        D_claim::where('vn',$value->vn)->update([ 
                            'sum_price'          => $value->price_ofc,  
                        ]);
                    } else {
                        D_claim::insert([
                            'vn'                => $value->vn,
                            'hn'                => $value->hn,
                            'an'                => $value->an,
                            'cid'               => $value->cid,
                            'pttype'            => $value->pttype,
                            'ptname'            => $value->ptname,
                            'vstdate'           => $value->vstdate,
                            'hipdata_code'      => $value->hipdata_code,
                            // 'qty'               => $value->qty,
                            'sum_price'          => $value->price_ofc,
                            'type'              => 'OPD',
                            'nhso_adp_code'     => 'OFC',
                            'claimdate'         => $date, 
                            'userid'            => $iduser, 
                        ]);
                    } 
                    D_dru_out::truncate();
                    $data_dru_ = DB::connection('mysql2')->select('
                        SELECT vv.hcode HCODE ,v.hn HN ,v.an AN ,vv.spclty CLINIC ,vv.cid PERSON_ID ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATE_SERV
                            ,d.icode DID ,concat(d.`name`," ",d.strength," ",d.units) DIDNAME ,v.qty AMOUNT ,round(v.unitprice,2) DRUGPRIC
                            ,"0.00" DRUGCOST ,d.did DIDSTD ,d.units UNIT ,concat(d.packqty,"x",d.units) UNIT_PACK ,v.vn SEQ
                            ,oo.presc_reason DRUGREMARK ,oo.nhso_authorize_code PA_NO ,"" TOTCOPAY ,if(v.item_type="H","2","1") USE_STATUS
                            ,"" TOTAL ,"" as SIGCODE ,"" as SIGTEXT ,""  PROVIDER,v.vstdate
                            FROM opitemrece v
                            LEFT OUTER JOIN drugitems d on d.icode = v.icode
                            LEFT OUTER JOIN vn_stat vv on vv.vn = v.vn
                            LEFT OUTER JOIN ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode                
                        WHERE v.vn IN("'.$value->vn.'")
                        AND d.did is not null 
                        GROUP BY v.vn,did

                        UNION all

                        SELECT pt.hcode HCODE ,v.hn HN ,v.an AN ,v1.spclty CLINIC ,pt.cid PERSON_ID ,DATE_FORMAT((v.vstdate),"%Y%m%d") DATE_SERV
                            ,d.icode DID ,concat(d.`name`," ",d.strength," ",d.units) DIDNAME ,sum(v.qty) AMOUNT ,round(v.unitprice,2) DRUGPRIC
                            ,"0.00" DRUGCOST ,d.did DIDSTD ,d.units UNIT ,concat(d.packqty,"x",d.units) UNIT_PACK ,v.vn SEQ
                            ,oo.presc_reason DRUGREMARK ,oo.nhso_authorize_code PA_NO ,"" TOTCOPAY ,if(v.item_type="H","2","1") USE_STATUS
                            ,"" TOTAL,"" as SIGCODE,"" as SIGTEXT,""  PROVIDER,v.vstdate
                            FROM opitemrece v
                            LEFT OUTER JOIN drugitems d on d.icode = v.icode
                            LEFT OUTER JOIN patient pt  on v.hn = pt.hn
                            INNER JOIN ipt v1 on v1.an = v.an
                            LEFT OUTER JOIN ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode                 
                        WHERE v1.vn IN("'.$value->vn.'")
                        AND d.did is not null AND v.qty<>"0"
                        GROUP BY v.an,d.icode,USE_STATUS;              
                    ');
            
                    foreach ($data_dru_ as $va_14) {
                        D_dru_out::insert([ 
                            'vstdate'        => $va_14->vstdate, 
                            'HN'             => $va_14->HN, 
                            'PERSON_ID'      => $va_14->PERSON_ID, 
                            'DID'            => $va_14->DID,
                            'DIDNAME'        => $va_14->DIDNAME, 
                            'AMOUNT'         => $va_14->AMOUNT,
                            'DRUGPRIC'       => $va_14->DRUGPRIC,
                            'DRUGCOST'       => $va_14->DRUGCOST,
                            'DIDSTD'         => $va_14->DIDSTD,
                            'UNIT'           => $va_14->UNIT,
                            'UNIT_PACK'      => $va_14->UNIT_PACK,
                            'SEQ'            => $va_14->SEQ,
                            'DRUGREMARK'     => $va_14->DRUGREMARK,
                            'PA_NO'          => $va_14->PA_NO 
                        ]);
                    } 
                    
                       
                }
              
        }

           

        return view('ct.ct_rep',[
            'startdate'     =>     $startdate,
            'enddate'       =>     $enddate, 
        ]);
    }
    public function ct_rep_import(Request $request)
    {
        $datenow = date('Y-m-d');
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $datashow = DB::connection('mysql')->select('
            SELECT ct_no,ct_date,SUM(remain) as Sumprice,STMdoc,month(ct_date) as months
            FROM a_stm_ct_excel
            GROUP BY ct_no
            ');
        $countc = DB::table('a_stm_ct_excel')->count(); 
        return view('ct.ct_rep_import',[
            'startdate'     =>     $startdate,
            'enddate'       =>     $enddate,
            'datashow'      =>     $datashow,
            'countc'        =>     $countc
        ]);
    }
    

    function ct_rep_import_save (Request $request)
    {  
        $the_file = $request->file('file'); 
        $file_ = $request->file('file')->getClientOriginalName(); //ชื่อไฟล์
        // dd($file_);
            try{                
             
                $spreadsheet = IOFactory::load($the_file); 
                $sheet        = $spreadsheet->setActiveSheetIndex(0);
                $row_limit    = $sheet->getHighestDataRow();
                $column_limit = $sheet->getHighestDataColumn();
                $row_range    = range( 5, $row_limit );
                $column_range = range( 'O', $column_limit );
                $startcount = 5;
                $data = array();

                // dd($data);
                foreach ($row_range as $row ) {
                    $vst = $sheet->getCell( 'B' . $row )->getValue();  
                    $day = substr($vst,0,2);
                    $mo = substr($vst,3,2);
                    $year = substr($vst,6,4);
                    // $year = $year_.'-543';
                    $ct_date = $year.'-'.$mo.'-'.$day; 

                    $l= $sheet->getCell( 'L' . $row )->getValue();
                    $del_l = str_replace(",","",$l);

                    $m = $sheet->getCell( 'M' . $row )->getValue();
                    $del_m = str_replace(",","",$m);
                    $o = $sheet->getCell( 'O' . $row )->getValue();
                    $del_o = str_replace(",","",$o);
                  
                    $iduser = Auth::user()->id;
                    $data[] = [
                        'ct_no'                   =>$sheet->getCell( 'A' . $row )->getValue(),
                        'ct_date'                 =>$ct_date,
                        'ct_timein'               =>$sheet->getCell( 'C' . $row )->getValue(),
                        'hn'                      =>$sheet->getCell( 'D' . $row )->getValue(),
                        'ptname'                  =>$sheet->getCell( 'E' . $row )->getValue(),
                        'hname'                   =>$sheet->getCell( 'F' . $row )->getValue(),
                        'pttypename'              =>$sheet->getCell( 'G' . $row )->getValue(), 
                        'ward'                    =>$sheet->getCell( 'H' . $row )->getValue(), 
                        'doctor'                  =>$sheet->getCell( 'I' . $row )->getValue(),  
                        'doctor_read'             =>$sheet->getCell( 'J' . $row )->getValue(), 
                        'check'                   =>$sheet->getCell( 'K' . $row )->getValue(),
                        'price_check'             =>$del_l,
                        'price_drug'              =>$del_m,
                        'qty_drug'                =>$sheet->getCell( 'N' . $row )->getValue(),
                        'remain'                  =>$del_o,  
                        'user_id'                 =>$iduser,  
                        'STMDoc'                  =>$file_ 
                    ];
                    $startcount++;  
                } 
                foreach (array_chunk($data,500) as $t)  
                { 
                    DB::table('a_stm_ct_excel')->insert($t);
                }
 
                // $the_file->delete('public/File_eclaim/'.$file_); 
                // $the_file->storeAs('Import/',$file_);   // ย้าย ไฟล์   
                // Storage::delete('File_CT/'.$file_);   // ลบไฟล์  
                // // ลบไฟล์   
                // if(file_exists(public_path('File_CT/'.$file_))){
                //     unlink(public_path('File_CT/'.$file_));
                //     // Storage::delete('File_eclaim/'.$file_);   // ลบไฟล์  
                // }else{
                //     dd('File does not exists.');
                // }
                
                
            } catch (Exception $e) {
                $error_code = $e->errorInfo[1];
                return back()->withErrors('There was a problem uploading the data!');
            }
            return redirect()->back();
            // return response()->json([
            //     'status'    => '200',
            // ]);
    }

    // public function ofc_401_repsend(Request $request)
    // {

    //     try{
    //         $data_ = DB::connection('mysql')->select(' SELECT * FROM d_ofc_repexcel');
    //             foreach ($data_ as $key => $value) {
    //                 if ($value->b != '') {
    //                     $check = D_ofc_rep::where('rep_a','=',$value->a)->where('no_b','=',$value->b)->count();
    //                     if ($check > 0) {
    //                     } else {
    //                         D_ofc_rep::insert([
    //                             'rep_a'                   =>$value->a,
    //                             'no_b'                    =>$value->b,
    //                             'tranid_c'                =>$value->c,
    //                             'hn_d'                    =>$value->d,
    //                             'an_e'                    =>$value->e,
    //                             'pid_f'                   =>$value->f,
    //                             'ptname_g'                =>$value->g, 
    //                             'type_h'                  =>$value->h,
    //                             'vstdate_i'               =>$value->i,
    //                             'dchdate_j'               =>$value->j,  
    //                             'price1_k'                =>$value->k,
    //                             'pp_spsch_l'              =>$value->l,
    //                             'errorcode_m'             =>$value->m,
    //                             'kongtoon_n'              =>$value->n,
    //                             'typeservice_o'           =>$value->o,
    //                             'refer_p'                 =>$value->p,
    //                             'pttype_have_q'           =>$value->q, 
    //                             'pttype_true_r'           =>$value->r, 
    //                             'mian_pttype_s'           =>$value->s, 
    //                             'secon_pttype_t'          =>$value->t, 
    //                             'href_u'                  =>$value->u, 
    //                             'HCODE_v'                 =>$value->v, 
    //                             'prov1_w'                 =>$value->w, 
    //                             'code_dep_x'              =>$value->x, 
    //                             'name_dep_y'              =>$value->y, 
    //                             'proj_z'                  =>$value->z, 
    //                             'pa_aa'                   =>$value->aa, 
    //                             'drg_ab'                  =>$value->ab, 
    //                             'rw_ac'                   =>$value->ac, 
    //                             'income_ad'               =>$value->ad, 
    //                             'pp_gep_ae'               =>$value->ae, 
    //                             'claim_true_af'           =>$value->af, 
    //                             'claim_false_ag'          =>$value->ag, 
    //                             'cash_money_ah'           =>$value->ah, 
    //                             'pay_ai'                  =>$value->ai, 
    //                             'ps_aj'                   =>$value->aj, 
    //                             'ps_percent_ak'           =>$value->ak, 
    //                             'ccuf_al'                 =>$value->al,
    //                             'AdjRW_am'                =>$value->am,
    //                             'plb_an'                  =>$value->an,
    //                             'IPCS_ao'                 =>$value->ao,
    //                             'IPCS_ORS_ap'             =>$value->ap,
    //                             'OPCS_aq'                 =>$value->aq,
    //                             'PACS_ar'                 =>$value->ar, 
    //                             'INSTCS_as'               =>$value->as, 
    //                             'OTCS_at'                 =>$value->at, 
    //                             'PP_au'                   =>$value->au, 
    //                             'DRUG_av'                 =>$value->av, 
    //                             'IPCS_aw'                 =>$value->aw,
    //                             'OPCS_AX'                 =>$value->ax,
    //                             'PACS_ay'                 =>$value->ay, 
    //                             'INSTCS_az'               =>$value->az, 
    //                             'OTCS_ba'                 =>$value->ba,
    //                             'ORS_bb'                  =>$value->bb, 
    //                             'VA_bc'                   =>$value->bc, 
    //                             'STMdoc'                  =>$value->STMdoc
    //                         ]);
                            
    //                     }
    //                 } else {
    //                 }
    //             }
    //         } catch (Exception $e) {
    //             $error_code = $e->errorInfo[1];
    //             return back()->withErrors('There was a problem uploading the data!');
    //         }
    //         D_ofc_repexcel::truncate();


    //     return redirect()->back();
    // }

    
 
}
