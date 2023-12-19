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
use App\Models\A_stm_ct_item;
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
            SELECT cid,ct_date,SUM(sumprice) as sumprice,SUM(paid) as paid,SUM(remain) as remain,STMdoc,month(ct_date) as months
            FROM a_stm_ct_excel
            WHERE cid is not null
            GROUP BY cid
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
                $row_range    = range( 3, $row_limit );
                $column_range = range( 'AI', $column_limit );
                $startcount = 3;
                $data = array();

                // dd($data);
                foreach ($row_range as $row ) {
                    $vst = $sheet->getCell( 'A' . $row )->getValue();  
                    if ( $vst != '') {
                        $day = substr($vst,0,2);
                        $mo = substr($vst,3,2);
                        $year = (substr($vst,6,4)-543);  
                        $ct_date = $year.'-'.$mo.'-'.$day; 
                    } else {
                        $ct_date = '0000-00-00';
                    }

                    // $year = ($year_.'-'.'543');
 
                    $o = $sheet->getCell( 'O' . $row )->getValue();
                    $del_o = str_replace(",","",$o);
                    $p = $sheet->getCell( 'P' . $row )->getValue();
                    $del_p = str_replace(",","",$p);
                    $r= $sheet->getCell( 'R' . $row )->getValue();
                    $del_r = str_replace(",","",$r);
                    $u= $sheet->getCell( 'U' . $row )->getValue();
                    $del_u = str_replace(",","",$u);
                    $v= $sheet->getCell( 'V' . $row )->getValue();
                    $del_v = str_replace(",","",$v);

                    $w= $sheet->getCell( 'W' . $row )->getValue();
                    $del_w = str_replace(",","",$w);
                    $w= $sheet->getCell( 'X' . $row )->getValue();
                    $del_x = str_replace(",","",$w);
                    $y= $sheet->getCell( 'Y' . $row )->getValue();
                    $del_y = str_replace(",","",$y);
                    $z= $sheet->getCell( 'Z' . $row )->getValue();
                    $del_z = str_replace(",","",$z);
                    $aa= $sheet->getCell( 'AA' . $row )->getValue();
                    $del_aa = str_replace(",","",$aa);
                    $ab= $sheet->getCell( 'AB' . $row )->getValue();
                    $del_ab = str_replace(",","",$ab);
                    $ac= $sheet->getCell( 'AC' . $row )->getValue();
                    $del_ac = str_replace(",","",$ac);

                    $iduser = Auth::user()->id;
                    $data[] = [ 
                        'ct_date'                 =>$ct_date,
                        'ct_timein'               =>$sheet->getCell( 'B' . $row )->getValue(),
                        'hn'                      =>$sheet->getCell( 'C' . $row )->getValue(),
                        'an'                      =>$sheet->getCell( 'D' . $row )->getValue(),
                        'cid'                     =>$sheet->getCell( 'E' . $row )->getValue(),
                        'ptname'                  =>$sheet->getCell( 'F' . $row )->getValue(),
                        'sfhname'                 =>$sheet->getCell( 'G' . $row )->getValue(), 
                        'typename'                =>$sheet->getCell( 'H' . $row )->getValue(), 
                        'pttypename'              =>$sheet->getCell( 'I' . $row )->getValue(),  
                        'hname'                   =>$sheet->getCell( 'J' . $row )->getValue(), 
                        'cardno'                  =>$sheet->getCell( 'K' . $row )->getValue(),
                        'ward'                    =>$sheet->getCell( 'L' . $row )->getValue(),
                        'service'                 =>$sheet->getCell( 'M' . $row )->getValue(),
                        'ct_check'                =>$sheet->getCell( 'N' . $row )->getValue(),
                        'price_check'             =>$del_o,
                        'total_price_check'       =>$del_p,
                        'opaque'                  =>$sheet->getCell( 'Q' . $row )->getValue(),
                        'opaque_price'            =>$del_r, 
                        'other'                   =>$sheet->getCell( 'T' . $row )->getValue(), 
                        'other_price'             =>$del_u, 
                        'total_other_price'       =>$del_v, 
                        'before_price'            =>$del_w,
                        'discount'                =>$del_x,
                        'vat'                     =>$del_y,
                        'total'                   =>$del_z,
                        'sumprice'                =>$del_aa,
                        'paid'                    =>$del_ab,
                        'remain'                  =>$del_ac, 
                        'doctor'                  =>$sheet->getCell( 'AD' . $row )->getValue(), 
                        'doctor_read'             =>$sheet->getCell( 'AE' . $row )->getValue(), 
                        'technician'              =>$sheet->getCell( 'AF' . $row )->getValue(), 
                        'technician_sub'          =>$sheet->getCell( 'AG' . $row )->getValue(), 
                        'nurse'                   =>$sheet->getCell( 'AH' . $row )->getValue(), 
                        'icd9'                    =>$sheet->getCell( 'AI' . $row )->getValue(),  
                        'user_id'                 =>$iduser,  
                        'STMDoc'                  =>$file_ 
                    ];
                    $startcount++;  

                    A_stm_ct_excel::insert([
                        'ct_date'                 =>$ct_date,
                        'ct_timein'               =>$sheet->getCell( 'B' . $row )->getValue(),
                        'hn'                      =>$sheet->getCell( 'C' . $row )->getValue(),
                        'an'                      =>$sheet->getCell( 'D' . $row )->getValue(),
                        'cid'                     =>$sheet->getCell( 'E' . $row )->getValue(),
                        'ptname'                  =>$sheet->getCell( 'F' . $row )->getValue(),
                        'sfhname'                 =>$sheet->getCell( 'G' . $row )->getValue(), 
                        'typename'                =>$sheet->getCell( 'H' . $row )->getValue(), 
                        'pttypename'              =>$sheet->getCell( 'I' . $row )->getValue(),  
                        'hname'                   =>$sheet->getCell( 'J' . $row )->getValue(), 
                        'cardno'                  =>$sheet->getCell( 'K' . $row )->getValue(),
                        'ward'                    =>$sheet->getCell( 'L' . $row )->getValue(),
                        'service'                 =>$sheet->getCell( 'M' . $row )->getValue(),
                        'ct_check'                =>$sheet->getCell( 'N' . $row )->getValue(),
                        'price_check'             =>$del_o,
                        'total_price_check'       =>$del_p,
                        'opaque'                  =>$sheet->getCell( 'Q' . $row )->getValue(),
                        'opaque_price'            =>$del_r, 
                        'other'                   =>$sheet->getCell( 'T' . $row )->getValue(), 
                        'other_price'             =>$del_u, 
                        'total_other_price'       =>$del_v, 
                        'before_price'            =>$del_w,
                        'discount'                =>$del_x,
                        'vat'                     =>$del_y,
                        'total'                   =>$del_z,
                        'sumprice'                =>$del_aa,
                        'paid'                    =>$del_ab,
                        'remain'                  =>$del_ac, 
                        'doctor'                  =>$sheet->getCell( 'AD' . $row )->getValue(), 
                        'doctor_read'             =>$sheet->getCell( 'AE' . $row )->getValue(), 
                        'technician'              =>$sheet->getCell( 'AF' . $row )->getValue(), 
                        'technician_sub'          =>$sheet->getCell( 'AG' . $row )->getValue(), 
                        'nurse'                   =>$sheet->getCell( 'AH' . $row )->getValue(), 
                        'icd9'                    =>$sheet->getCell( 'AI' . $row )->getValue(),  
                        'user_id'                 =>$iduser,  
                        'STMDoc'                  =>$file_ 
                        
                    ]);

                } 
                // foreach (array_chunk($data,500) as $t)  
                // { 
                //     DB::table('a_stm_ct_excel')->insert($t);
                // }
                 
                
            } catch (Exception $e) {
                $error_code = $e->errorInfo[1];
                return back()->withErrors('There was a problem uploading the data!');
            }

            $data_ = DB::connection('mysql')->select('SELECT * FROM a_stm_ct_excel');
       
                foreach ($data_ as $key => $value) {
                    // if ($value->ct_check != '') {
                    //     $check = A_stm_ct_item::where('ct_date','=',$value->ct_date)->where('cid','=',$value->cid)->count();
                    //     if ($check > 0) {
                    //     } else {
                    //         A_stm_ct_item::insert([
                    //             'ct_date'                  =>$value->ct_date, 
                    //             'hn'                       =>$value->hn,
                    //             'an'                       =>$value->an,
                    //             'cid'                      =>$value->cid,
                    //             'ptname'                   =>$value->ptname, 
                    //             'ct_check'                 =>$value->ct_check,
                    //             'price_check'              =>$value->price_check,
                    //             'total_price_check'        =>$value->total_price_check, 
                    //             'opaque_price'             =>$value->opaque_price, 
                    //             'total_opaque_price'       =>$value->total_opaque_price,   
                    //             'before_price'             =>$value->before_price, 
                    //             'discount'                 =>$value->discount, 
                    //             'vat'                      =>$value->vat, 
                    //             'total'                    =>$value->total, 
                    //             'sumprice'                 =>$value->sumprice, 
                    //             'paid'                     =>$value->paid, 
                    //             'remain'                   =>$value->remain,  
                    //             'user_id'                  =>$value->user_id,  
                    //         ]);
                            
                    //     }
                    // } else {
                    // }
                }



                // A_stm_ct_excel::truncate(); 

            return redirect()->route('ct.ct_rep_import');
            // return response()->json([
            //     'status'    => '200',
            // ]);
    }

    public function ct_rep_import_send(Request $request)
    {

        try{
            $data_ = DB::connection('mysql')->select('SELECT * FROM a_stm_ct_excel');
            // dd($data_);
                foreach ($data_ as $key => $value) {
                    if ($value->ct_check != '') {
                        $check = A_stm_ct::where('ct_date','=',$value->ct_date)->where('cid','=',$value->cid)->count();
                        if ($check > 0) {
                        } else {
                            A_stm_ct::insert([
                                'ct_date'                  =>$value->ct_date,
                                'ct_timein'                =>$value->ct_timein,
                                'hn'                       =>$value->hn,
                                'an'                       =>$value->an,
                                'cid'                      =>$value->cid,
                                'ptname'                   =>$value->ptname,
                                'sfhname'                  =>$value->sfhname, 
                                'typename'                 =>$value->typename,
                                'pttypename'               =>$value->pttypename,
                                'hname'                    =>$value->hname,  
                                'cardno'                   =>$value->cardno,
                                'ward'                     =>$value->ward,
                                'service'                  =>$value->service,
                                'ct_check'                 =>$value->ct_check,
                                'price_check'              =>$value->price_check,
                                'total_price_check'        =>$value->total_price_check,
                                'opaque'                   =>$value->opaque, 
                                'opaque_price'             =>$value->opaque_price, 
                                'total_opaque_price'       =>$value->total_opaque_price, 
                                'other'                    =>$value->other, 
                                'other_price'              =>$value->other_price, 
                                'total_other_price'        =>$value->total_other_price, 
                                'before_price'             =>$value->before_price, 
                                'discount'                 =>$value->discount, 
                                'vat'                      =>$value->vat, 
                                'total'                    =>$value->total, 
                                'sumprice'                 =>$value->sumprice, 
                                'paid'                     =>$value->paid, 
                                'remain'                   =>$value->remain, 
                                'doctor'                   =>$value->doctor, 
                                'doctor_read'              =>$value->doctor_read, 
                                'technician'               =>$value->technician, 
                                'technician_sub'           =>$value->technician_sub, 
                                'nurse'                    =>$value->nurse, 
                                'icd9'                     =>$value->icd9, 
                                'user_id'                  =>$value->user_id, 
                                'STMDoc'                   =>$value->STMDoc, 
                                'vn'                       =>$value->vn,
                                'hos_check'                =>$value->hos_check,
                                'hos_price_check'          =>$value->hos_price_check,
                                'hos_total_price_check'    =>$value->hos_total_price_check,
                                
                            ]);
                            
                        }
                    } else {
                    }
                }
            } catch (Exception $e) {
                $error_code = $e->errorInfo[1];
                return back()->withErrors('There was a problem uploading the data!');
            }

            A_stm_ct_excel::truncate(); 

            return redirect()->route('ct.ct_rep_import');
    }

    
 
}
