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
use App\Models\Acc_1102050101_216;
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
use App\Models\Acc_1102050102_8011;
use App\Models\Acc_stm_prb;
use App\Models\Acc_stm_ti_totalhead;
use App\Models\Acc_stm_ti_excel;
use App\Models\Acc_stm_ofc;
use App\Models\Acc_stm_ofcexcel;
use App\Models\Acc_stm_lgo;
use App\Models\Acc_stm_lgoexcel;
use App\Models\Check_sit_auto;
use App\Models\Acc_stm_ucs_excel;
use App\Models\Acc_stm_repmoney;
use App\Models\Acc_stm_lgoti_excel;
use App\Models\Acc_stm_lgoti;
use App\Models\Acc_stm_repmoney_file;
use App\Models\Acc_trimart;

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


class AccountSTMONEIDController extends Controller
{    

    public function stm_oneid_opd(Request $request)
    {
        $datenow = date('Y-m-d');
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $datashow = DB::connection('mysql')->select('
            SELECT rep,vstdate,SUM(ip_paytrue) as Sumprice,STMdoc,month(vstdate) as months
            FROM acc_stm_ucs_excel
            WHERE cid IS NOT NULL
            GROUP BY rep
            ');
        $countc = DB::table('acc_stm_ucs_excel')->count(); 

        return view('account_pk.stm_oneid_opd',[
            'startdate'     =>     $startdate,
            'enddate'       =>     $enddate,
            'datashow'      =>     $datashow,
            'countc'        =>     $countc
        ]);
    }
    function stm_oneid_opdsave(Request $request)
    { 
        // $this->validate($request, [
        //     'file' => 'required|file|mimes:xls,xlsx'
        // ]);
        $the_file = $request->file('file_stm'); 
        $file_ = $request->file('file_stm')->getClientOriginalName(); //ชื่อไฟล์

        // dd($the_file);
                $spreadsheet = IOFactory::load($the_file->getRealPath()); 
                $sheet        = $spreadsheet->setActiveSheetIndex(2);
                $row_limit    = $sheet->getHighestDataRow();
                // $column_limit = $sheet->getHighestDataColumn();
                $row_range    = range('15',$row_limit ); 
                $startcount = '15';
                $data = array();
                foreach ($row_range as $row ) {
                    $vst = $sheet->getCell( 'H' . $row )->getValue();  
                    $day = substr($vst,0,2);
                    $mo = substr($vst,3,2);
                    $year = substr($vst,6,4);
                    $vstdate = $year.'-'.$mo.'-'.$day;

                    $reg = $sheet->getCell( 'I' . $row )->getValue(); 
                    $regday = substr($reg, 0, 2);
                    $regmo = substr($reg, 3, 2);
                    $regyear = substr($reg, 6, 4);
                    $dchdate = $regyear.'-'.$regmo.'-'.$regday;

                    $l = $sheet->getCell( 'L' . $row )->getValue();
                    $del_l = str_replace(",","",$l);
                    $m = $sheet->getCell( 'M' . $row )->getValue();
                    $del_m = str_replace(",","",$m);
                    $n = $sheet->getCell( 'N' . $row )->getValue();
                    $del_n = str_replace(",","",$n);
                    $q = $sheet->getCell( 'Q' . $row )->getValue();
                    $del_q = str_replace(",","",$q);
                    $r = $sheet->getCell( 'R' . $row )->getValue();
                    $del_r = str_replace(",","",$r);
                    $s = $sheet->getCell( 'S' . $row )->getValue();
                    $del_s = str_replace(",","",$s);
                    $t = $sheet->getCell( 'T' . $row )->getValue();
                    $del_t = str_replace(",","",$t);
                    $u = $sheet->getCell( 'U' . $row )->getValue();
                    $del_u = str_replace(",","",$u);
                    $v = $sheet->getCell( 'V' . $row )->getValue();
                    $del_v = str_replace(",","",$v);
                    $w = $sheet->getCell( 'W' . $row )->getValue();
                    $del_w = str_replace(",","",$w);
                    $x = $sheet->getCell( 'X' . $row )->getValue();
                    $del_x = str_replace(",","",$x);
                    $y = $sheet->getCell( 'Y' . $row )->getValue();
                    $del_y = str_replace(",","",$y);
                    $z = $sheet->getCell( 'Z' . $row )->getValue();
                    $del_z = str_replace(",","",$z);
                    $aa = $sheet->getCell( 'AA' . $row )->getValue();
                    $del_aa = str_replace(",","",$aa);
                    $ab = $sheet->getCell( 'AB' . $row )->getValue();
                    $del_ab = str_replace(",","",$ab);
                    $ac = $sheet->getCell( 'AC' . $row )->getValue();
                    $del_ac = str_replace(",","",$ac);
                    $ad = $sheet->getCell( 'AD' . $row )->getValue();
                    $del_ad = str_replace(",","",$ad);
                    $ae = $sheet->getCell( 'AE' . $row )->getValue();
                    $del_ae = str_replace(",","",$ae);
                    $af = $sheet->getCell( 'AF' . $row )->getValue();
                    $del_af = str_replace(",","",$af);
                    $ag = $sheet->getCell( 'AG' . $row )->getValue();
                    $del_ag = str_replace(",","",$ag);
                    $ah = $sheet->getCell( 'AH' . $row )->getValue();
                    $del_ah = str_replace(",","",$ah);
                    $ai = $sheet->getCell( 'AI' . $row )->getValue();
                    $del_ai = str_replace(",","",$ai);
                    $aj = $sheet->getCell( 'AJ' . $row )->getValue();
                    $del_aj = str_replace(",","",$aj);
                    $ak = $sheet->getCell( 'AK' . $row )->getValue();
                    $del_ak = str_replace(",","",$ak);
                    $al = $sheet->getCell( 'AL' . $row )->getValue();
                    $del_al = str_replace(",","",$al);
                    $an = $sheet->getCell( 'AN' . $row )->getValue();
                    $del_an = str_replace(",","",$an);
 
                    $data[] = [
                        'rep'                   =>$sheet->getCell( 'A' . $row )->getValue(),
                        'repno'                 =>$sheet->getCell( 'B' . $row )->getValue(),
                        'tranid'                =>$sheet->getCell( 'C' . $row )->getValue(),
                        'hn'                    =>$sheet->getCell( 'D' . $row )->getValue(),
                        'an'                    =>$sheet->getCell( 'E' . $row )->getValue(),
                        'cid'                   =>$sheet->getCell( 'F' . $row )->getValue(),
                        'fullname'              =>$sheet->getCell( 'G' . $row )->getValue(), 
                        'vstdate'               =>$vstdate,
                        'dchdate'               =>$dchdate, 
                        'maininscl'             =>$sheet->getCell( 'J' . $row )->getValue(),
                        'projectcode'           =>$sheet->getCell( 'K' . $row )->getValue(),
                        'debit'                 =>$del_l,
                        'debit_prb'             =>$del_m,
                        'adjrw'                 =>$del_n,
                        'ps1'                   =>$sheet->getCell( 'O' . $row )->getValue(),
                        'ps2'                   =>$sheet->getCell( 'P' . $row )->getValue(),
                        'ccuf'                  =>$del_q,
                        'adjrw2'                =>$del_r, 
                        'pay_money'             => $del_s,
                        'pay_slip'              => $del_t,
                        'pay_after'             => $del_u,
                        'op'                    => $del_v,
                        'ip_pay1'               => $del_w,
                        'ip_paytrue'            => $del_x,
                        'hc'                    => $del_y,
                        'hc_drug'               => $del_z,
                        'ae'                    => $del_aa,
                        'ae_drug'               => $del_ab,
                        'inst'                  => $del_ac,
                        'dmis_money1'           => $del_ad,
                        'dmis_money2'           => $del_ae,
                        'dmis_drug'             => $del_af,
                        'palliative_care'       => $del_ag,
                        'dmishd'                => $del_ah,
                        'pp'                    => $del_ai,
                        'fs'                    => $del_aj,
                        'opbkk'                 => $del_ak,
                        'total_approve'         => $del_al, 
                        'va'                    =>$sheet->getCell( 'AM' . $row )->getValue(),
                        'covid'                 =>$del_an,
                        'ao'                    =>$sheet->getCell( 'AO' . $row )->getValue(),
                        'STMdoc'                =>$file_ 
                    ];
                    $startcount++;  
                } 
                $for_insert = array_chunk($data, length:1000);
                foreach ($for_insert as $key => $data_) {
                    Acc_stm_ucs_excel::insert($data_); 
                }
                 
                
  
            return redirect()->back();
          
    }
    public function stm_oneid_opdsend(Request $request)
    {
        try{
            $data_ = DB::connection('mysql')->select('SELECT * FROM acc_stm_ucs_excel WHERE cid IS NOT NULL');
            foreach ($data_ as $key => $value) {
                if ($value->cid != '') {
                    $check = Acc_stm_ucs::where('tranid','=',$value->tranid)->count();
                    if ($check > 0) {
                    } else {
                        $add = new Acc_stm_ucs();
                        $add->rep            = $value->rep;
                        $add->repno          = $value->repno;
                        $add->tranid         = $value->tranid;
                        $add->hn             = $value->hn;
                        $add->an             = $value->an;
                        $add->cid            = $value->cid;
                        $add->fullname       = $value->fullname;
                        $add->vstdate        = $value->vstdate;
                        $add->dchdate        = $value->dchdate;
                        $add->maininscl      = $value->maininscl;
                        $add->projectcode    = $value->projectcode;
                        $add->debit          = $value->debit;
                        $add->debit_prb      = $value->debit_prb;
                        $add->adjrw          = $value->adjrw;
                        $add->ps1            = $value->ps1;
                        $add->ps2            = $value->ps2;

                        $add->ccuf           = $value->ccuf;
                        $add->adjrw2         = $value->adjrw2;
                        $add->pay_money      = $value->pay_money;
                        $add->pay_slip       = $value->pay_slip;
                        $add->pay_after      = $value->pay_after;
                        $add->op             = $value->op;
                        $add->ip_pay1        = $value->ip_pay1;
                        $add->ip_paytrue     = $value->ip_paytrue;
                        $add->hc             = $value->hc;
                        $add->hc_drug        = $value->hc_drug;
                        $add->ae             = $value->ae;
                        $add->ae_drug        = $value->ae_drug;
                        $add->inst           = $value->inst;
                        $add->dmis_money1    = $value->dmis_money1;
                        $add->dmis_money2    = $value->dmis_money2;
                        $add->dmis_drug      = $value->dmis_drug;
                        $add->palliative_care = $value->palliative_care;
                        $add->dmishd         = $value->dmishd;
                        $add->pp             = $value->pp;
                        $add->fs             = $value->fs;
                        $add->opbkk          = $value->opbkk;
                        $add->total_approve  = $value->total_approve;
                        $add->va             = $value->va;
                        $add->covid          = $value->covid;
                        $add->date_save      = $value->date_save;
                        $add->STMdoc         = $value->STMdoc;
                        $add->save(); 
                     
                    } 
                    
                    if ($value->projectcode ='WALKIN') { 
                            Acc_1102050101_216::where('hn',$value->hn)->where('vstdate',$value->vstdate)
                                ->update([
                                    'status'          => 'Y',
                                    'stm_rep'         => $value->debit,
                                    'stm_money'       => $value->total_approve,
                                    'stm_rcpno'       => $value->rep.'-'.$value->repno,
                                    'stm_trainid'     => $value->tranid,
                                    'stm_total'       => $value->total_approve,
                                    'STMDoc'          => $value->STMdoc,
                                    'va'              => $value->va,
                            ]); 
                    } else {  
                        if ($value->hc_drug+$value->hc+$value->ae+$value->ae_drug+$value->inst+$value->dmis_money2+$value->dmis_drug == "0") {
                            
                            Acc_1102050101_216::where('hn',$value->hn)->where('vstdate',$value->vstdate)
                                ->update([
                                    'status'          => 'Y',
                                    'stm_rep'         => $value->debit, 
                                    'stm_rcpno'       => $value->rep.'-'.$value->repno,
                                    'stm_trainid'     => $value->tranid,
                                    'stm_total'       => $value->total_approve,
                                    'STMDoc'          => $value->STMdoc,
                                    'va'              => $value->va,
                            ]);
                            
                        }else if ($value->hc_drug+$value->hc+$value->ae+$value->ae_drug+$value->inst+$value->dmis_money2+$value->dmis_drug > "0") {
                            
                            Acc_1102050101_216::where('hn',$value->hn)->where('vstdate',$value->vstdate)
                                ->update([
                                    'status'          => 'Y',
                                    'stm_rep'         => $value->debit,
                                    'stm_money'       => $value->hc_drug + $value->hc + $value->ae + $value->ae_drug + $value->inst + $value->dmis_money2 + $value->dmis_drug,
                                    'stm_rcpno'       => $value->rep.'-'.$value->repno,
                                    'stm_trainid'     => $value->tranid,
                                    'stm_total'       => $value->total_approve,
                                    'STMDoc'          => $value->STMdoc,
                                    'va'              => $value->va,
                            ]);
                        } else {    
                        }
 
                    } 
  
                } else {
                }
            }
            } catch (Exception $e) {
                $error_code = $e->errorInfo[1];
                return back()->withErrors('There was a problem uploading the data!');
            }
            Acc_stm_ucs_excel::truncate();
        return redirect()->back();
    }
   

}
